<?php

namespace App\Http\Controllers;

use App\Models\Web;
use App\Services\CrawlerService;
use App\Services\ImageService;
use App\Services\WebService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    private const IMAGES_IN_ROW = 4;

    public function parsedSites()
    {
        $sites = Web::all();

        return view('parsed_sites', ['websites' => $sites]);
    }

    /**
     * @param Request $request
     * @param WebService $webService
     * @param CrawlerService $crawlerService
     * @param ImageService $imageService
     * @return RedirectResponse
     * @throws \Exception
     */
    public function parse(
        Request        $request,
        WebService     $webService,
        CrawlerService $crawlerService,
        ImageService   $imageService
    ): RedirectResponse
    {
        $websiteToParse = $request->get('website');

        if ($websiteToParse) {
            $client = new Client();
            try {
                $response = $client->get($websiteToParse);
                if ($response->getStatusCode() === Response::HTTP_OK) {
                    $web = Web::whereUrl($websiteToParse)->first();
                    if (!$web) {
                        $web = $webService->createWeb($websiteToParse);
                    }
                    $htmlString = (string)$response->getBody();
                    $imagesUrls = $crawlerService->getImagesDataFromHTML($htmlString);
                    foreach ($imagesUrls as $name => $imageUrl) {
                        if ($imageUrl) {
                            if (!str_contains($imageUrl, 'http')) {
                                $imageUrl = $this->relativeURLtoAbsolute($imageUrl, $websiteToParse);
                            }
                            $imageService->createImage($name, $imageUrl, $web);
                        }
                    }

                    return redirect()->route('parsed.url', ['web' => $web]);
                }
            } catch (GuzzleException $e) {
                return back()->withErrors(['msg' => $e->getMessage()]);
            }
        }
        return back()->withErrors(['msg' => 'Something went wrong']);
    }

    /**
     * @param Web $web
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function parsed(Web $web): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $images = $web->images;
        $size = 0;

        foreach ($images as $image) {
            $size += Storage::disk('public')->size('/' . $image->web->host . '/' . $image->name);
        }

        $rows = ceil(count($images) / self::IMAGES_IN_ROW);

        $offset = 0;
        $imagesArray = [];
        for ($i = 1; $i <= $rows; $i++) {
            $imagesArray[] = $images->slice($offset, self::IMAGES_IN_ROW);
            $offset += 4;
        }

        $bytes = number_format($size / 1048576, 2) . ' МБ';

        return view('parsed', [
            'web' => $web,
            'parsedImages' => $imagesArray,
            'parsedImagesCount' => count($images),
            'totalSize' => $bytes,
        ]);
    }

    /**
     * @param Web $web
     * @param ImageService $imageService
     * @return RedirectResponse
     */
    public function destroyParsedSite(Web $web, ImageService $imageService): RedirectResponse
    {
        $images = $web->images;
        foreach ($images as $image) {
            $imageService->destroyImage($image);
        }

        Storage::disk('public')->deleteDirectory($web->host);
        $web->delete();

        return redirect()->route('parsed.sites');
    }

    /**
     * @param $relative
     * @param $base
     * @return string
     */
    private function relativeURLtoAbsolute($relative, $base): string
    {
        $urlParts = parse_url($base);

        return $urlParts['scheme'] . '://' . $urlParts['host'] . $relative;
    }

}

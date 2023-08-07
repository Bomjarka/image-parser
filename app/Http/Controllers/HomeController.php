<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function parse(Request $request)
    {
        $websiteToParse = $request->get('website');

        if ($websiteToParse) {
            $client = new Client();
            try {
                $response = $client->get($websiteToParse);
                if ($response->getStatusCode() === Response::HTTP_OK) {
                    $htmlString = (string) $response->getBody();
                    $crawler = new Crawler($htmlString);
                    $elements = $crawler->filter('img')->each(function (Crawler $node, $i) {
                        return $node;
                    });
                    $imagesUrls = [];
                    foreach ($elements as $item) {
                        $imagesUrls[$item->filter('img')->attr('alt')] =  $item->filter('img')->attr('src');
                    }

                    $savedImageNames = [];
                    foreach ($imagesUrls as $alt => $url) {
                        if (str_contains($url, 'http')) {
                            $content = file_get_contents($url);
                            $name = $alt . '-' . Str::random(5) . '.jpg';
                            Storage::disk('public')->put($name, $content);
                            $savedImageNames[] = $name;
                        }
                    }

                    return redirect()->route('parsed.url')->with([
                        'parsedWebsite' => $websiteToParse,
                        'parsedImages' => $savedImageNames,
                        'foundImagesCount' => count($imagesUrls),
                    ]);
                }
            } catch (GuzzleException $e) {
                return $e->getMessage();
            }
        }
    }

    public function parsed()
    {
        $parsedWebsite = session()->get('parsedWebsite');
        $parsedImages = session()->get('parsedImages');
        $foundImagesCount = session()->get('foundImagesCount');

        $size = 0;

        foreach ($parsedImages as $parsedImage) {
            $size += Storage::disk('public')->size($parsedImage);
        }

        $rows = ceil(count($parsedImages) / 4);
        $offset = 0;
        $images = [];
        for ($i = 1; $i <= $rows; $i++) {
            $images[] = array_slice($parsedImages, $offset, 4);
            $offset += 4;
        }

        $bytes = number_format($size / 1048576, 2) . ' МБ';

        return view('parsed', [
            'parsedWebsite' => $parsedWebsite,
            'parsedImages' => $images,
            'parsedImagesCount' => count($parsedImages),
            'foundImagesCount' => $foundImagesCount,
            'totalSize' => $bytes,
        ]);
    }
}

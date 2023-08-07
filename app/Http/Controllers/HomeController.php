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

                    foreach ($imagesUrls as $alt => $url) {
                        if (str_contains($url, 'http')) {
                            $content = file_get_contents($url);
                            Storage::disk('local')->put($alt . '-' . Str::random(5) . 'jpg', $content);
                        }

                    }
                }
            } catch (GuzzleException $e) {
                return $e->getMessage();
            }
        }
    }
}

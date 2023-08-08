<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    /**
     * @param string $html
     * @return array
     * @throws \Exception
     */
    public function getImagesDataFromHTML(string $html): array
    {
        $crawler = new Crawler($html);
        $imagesData = $crawler
            ->filterXpath('//img')
            ->extract(['alt', 'src']);

        $imagesUrls = [];
        foreach ($imagesData as $imageData) {
            $nameHash = Str::substr(md5(Str::random(5)), random_int(1, 5), 5);
            $imagesUrls[$imageData[0] . '-' . $nameHash . '.jpg'] = $imageData[1];
        }

        return $imagesUrls;
    }
}

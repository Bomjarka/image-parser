<?php

namespace Tests\Unit;

use App\Services\CrawlerService;
use PHPUnit\Framework\TestCase;

class CrawlerServiceTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_can_get_image_from_html(): void
    {
        $crawlerService = new CrawlerService();
        $html = '
                <h1>test</h1>
                <img src="storage/images/test.jpg" alt="test.jpg">
                ';
        $urls = $crawlerService->getImagesDataFromHTML($html);
        $this->assertNotEmpty($urls);
        $this->assertArrayHasKey(array_keys($urls)[0], $urls);
        $this->assertEquals('storage/images/test.jpg', $urls[array_keys($urls)[0]]);
    }
}

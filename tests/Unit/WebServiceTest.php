<?php

namespace Tests\Unit;

use App\Services\ImageService;
use App\Services\WebService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WebServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_that_can_get_host_from_url(): void
    {
        $url = 'http://test.com/bla-bla';
        $host = 'test.com';
        $crawlerService = new WebService();
        $this->assertEquals($host, $crawlerService->getHost($url));
    }

    public function test_that_can_create_web(): void
    {
        $url = 'http://test.com/bla-bla';
        $webService = new WebService();
        $web = $webService->createWeb($url);
        $this->assertDatabaseHas('webs', [
            'url' => $web->url,
            'host' => $web->host,
        ]);
    }
}

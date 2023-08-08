<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Web;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * @param string $name
     * @param string $url
     * @param Web $web
     */
    public function createImage(string $name, string $url, Web $web): void
    {
        $content = file_get_contents($url);
        $this->storeFile($web->host . '/' . $name, $content);
        $image = new Image();
        $image->web_id = $web->id;
        $image->name = $name;
        $image->save();
    }

    /**
     * @param Image $image
     * @return void
     */
    public function destroyImage(Image $image): void
    {
        Storage::disk('public')->delete($image->web->host . '/' . $image->name);
        $image->delete();
    }

    /**
     * @param $name
     * @param $content
     * @return void
     */
    private function storeFile($name, $content): void
    {
        Storage::disk('public')->put($name, $content);
    }
}

<?php

namespace App\Services;

use App\Models\Web;

class WebService
{
    /**
     * @param string $url
     * @return mixed
     */
    public function getHost(string $url): mixed
    {
        return parse_url($url)['host'];
    }

    /**
     * @param string $url
     * @return Web
     */
    public function createWeb(string $url): Web
    {
        $host = $this->getHost($url);
        $web = new Web();
        $web->url = $url;
        $web->host = $host;
        $web->save();

        return $web;
    }


}

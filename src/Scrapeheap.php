<?php

namespace Coderjerk\Scrapeheap;

use Coderjerk\Scrapeheap\Spider;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;


class Scrapeheap
{

    public function scrape($target_url)
    {

        $base_domain = parse_url($target_url, PHP_URL_HOST);

        Roach::startSpider(
            Spider::class,
            new Overrides(startUrls: [$target_url]),
            context: ['base_domain' => $base_domain],
        );

        echo "all done!";
    }
}

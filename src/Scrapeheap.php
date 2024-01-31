<?php

namespace Coderjerk\Scrapeheap;

use Coderjerk\Scrapeheap\Spider;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;


class Scrapeheap
{

    /**
     * Let's scrape.
     *
     * @param string $target_url
     * @return void
     */
    public function scrape(string $target_url): void
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

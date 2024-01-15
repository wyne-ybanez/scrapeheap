<?php

namespace Coderjerk\Scrapeheap;

use Coderjerk\Scrapeheap\Spider;
use Coderjerk\Scrapeheap\Document;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;


class Scrapeheap
{

    public function scrape($target_url)
    {

        $items = Roach::collectSpider(
            Spider::class,
            new Overrides(startUrls: [$target_url]),
        );

        if ($items) {
            foreach ($items as $item) :
                if ($item->has('title') && $item->has('content')) {
                    $title = $item->get('title');
                    $content = $item->get('content');
                    $uri = $item->get('uri');
                    Document::make($uri, $title, $content);
                }
            endforeach;
        }

        echo "all done!";
    }
}

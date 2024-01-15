<?php

namespace Coderjerk\Scrapeheap;

use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;

class Spider extends BasicSpider
{
    /**
     * @var string[]
     */
    public array $startUrls = [
        'https://roach-php.dev/docs/spiders'
    ];

    public function parse(Response $response): \Generator
    {

        $links = $response->filter('a')->links();

        if ($links) {
            foreach ($links as $link) {
                yield $this->request('GET', $link->getUri(), 'parsePage');
            }
        }
    }

    public function parsePage(Response $response): \Generator
    {
        $title = 'default';
        $content = 'default';
        $current_uri = $response->getUri();

        if ($response->filter('title')->count() > 0) {

            $title = $response->filter('title')->text();
        }

        if ($response->filter('body')->count() > 0) {

            $content = $response
                ->filter('body')
                ->reduce(function ($node, $i): bool {
                    // filters every other node
                    return ($i % 2) === 0;
                })
                ->text();
        }

        yield $this->item([
            'title' => $title,
            'content' => $content,
            'uri' => $current_uri
        ]);

        $links = $response->filter('a')->links();

        if ($links) {
            foreach ($links as $link) {
                yield $this->request('GET', $link->getUri(), 'parsePage');
            }
        }
    }
}

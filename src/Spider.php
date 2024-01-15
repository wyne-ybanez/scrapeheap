<?php

namespace Coderjerk\Scrapeheap;

use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use Coderjerk\Scrapeheap\Document;

class Spider extends BasicSpider
{

    /**
     * @var string[]
     */
    public array $startUrls = [
        'https://roach-php.dev/docs/spiders'
    ];

    /**
     * @var string
     */
    public string $base_domain;

    /**
     * Get all links on a page and then send to be parsed.
     *
     * @param Response $response
     * @return \Generator
     */
    public function parse(Response $response): \Generator
    {

        $links = $response->filter('a')->links();

        if ($links) {
            foreach ($links as $link) {
                yield $this->request('GET', $link->getUri(), 'parsePage');
            }
        }
    }

    /**
     * Determines if the url is internal and a valid http/s url.
     *
     * @param object $link
     * @return boolean
     */
    private function checkUrl(object $link): bool
    {
        if (!parse_url($link->getUri(), PHP_URL_SCHEME) && parse_url($link->getUri(), PHP_URL_HOST)) {
            return false;
        }

        $domain = parse_url($link->getUri(), PHP_URL_HOST);

        if ($domain !== $this->context['base_domain']) {
            return false;
        }

        return true;
    }

    /**
     * Parses the page and writes the data to individual MS Word docs.
     *
     * @param Response $response
     * @return \Generator
     */
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
                ->text();
        }

        Document::make($current_uri, $title, $content);

        yield $this->item([
            'title' => $title,
            'content' => $content,
            'uri' => $current_uri
        ]);

        $links = $response->filter('a')->links();

        $crawled = [];

        if ($links) {
            foreach ($links as $link) {
                if ($this->checkUrl($link)) {
                    if (!in_array($link->getUri(), $crawled)) {
                        yield $this->request('GET', $link->getUri(), 'parsePage');
                    } else {
                        array_push($crawled, $link->getUri());
                    }
                }
            }
        }
    }
}

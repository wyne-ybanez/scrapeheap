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
     * Keeps track of links that have already been crawled.
     *
     * @var array
     */
    public array $crawled = [];

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
                if (!in_array($link->getUri(), $this->crawled)) {
                    if ($this->checkUrl($link)) {
                        yield $this->request('GET', $link->getUri(), 'parsePage');
                    }
                } else {
                    array_push($this->crawled, $link->getUri());
                }
            }
        }
    }

    /**
     * Determines if the url is internal and a probably valid http/s url.
     *
     * @param object $link
     * @return boolean
     */
    private function checkUrl(object $link): bool
    {
        // does it look like a url
        if (!parse_url($link->getUri(), PHP_URL_SCHEME) && parse_url($link->getUri(), PHP_URL_HOST)) {
            return false;
        }

        // does it start with http/s
        // TODO: some testing proves this scraper only takes urls with 'http' not 'https'
        $pattern = '/(http[s]?\:\/\/)?(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}/';

        if (!preg_match($pattern, $link->getUri())) {
            return false;
        };

        //does the host match the base url host
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
            $response
                ->filter('body nav, body script, body style, body footer, body noscript, div.elementor-location-header, div.elementor-lcoation-footer') //what we dont want to see
                ->each(function ($html_tag) {
                    $html_tag->getNode(0)->parentNode->removeChild($html_tag->getNode(0)); // remove these elements from DOM
                });

            $content = $response->filter('body')->text();

            dump($content);
        }

        //Document::make($current_uri, $title, $content);

        yield $this->item([
            'title' => $title,
            'content' => $content,
            'uri' => $current_uri
        ]);

        $links = $response->filter('a')->links();


        if ($links) {
            foreach ($links as $link) {
                if (!in_array($link->getUri(), $this->crawled)) {
                    if ($this->checkUrl($link)) {
                        yield $this->request('GET', $link->getUri(), 'parsePage');
                    }
                } else {
                    array_push($this->crawled, $link->getUri());
                }
            }
        }
    }
}

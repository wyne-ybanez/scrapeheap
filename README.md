# Scrapeheap

Originally from [Dan Devine](https://github.com/danieldevine) - A Content Scraping Tool

- [Scrapeheap](#scrapeheap)
  - [Instructions](#instructions)
  - [Latest Updates](#latest-updates)
  - [Local Deployment](#local-deployment)
  - [References](#references)

&nbsp;

## Instructions

Right now this version of scrapeheap is at it's infancy. Don't expect any fancy user interface. Just pop a URL in and expect results. Simple as that.

&nbsp;

## Latest Updates

1. See the dump of content as your scraper works
2. Saves Docx & HTML in separate folders
3. Adds some nice helpful text so if you want to scrape again, just go ahead

&nbsp;

## Local Deployment

1. Download/Clone the project
2. Install dependencies by running `composer install && npm install`
3. Ensure you put put the project where your valet has been parked in
4. Access the project locally via Valet at http://scrapeheap.test

This assumes you have Valet installed and properly configured for your project. If not, please refer to the [Valet documentation](https://laravel.com/docs/10.x/valet) for setup instructions.

&nbsp;

## References

- We're using RoachPHP here: https://roach-php.dev/docs/introduction

- Check out Dan's original project on this: https://github.com/danieldevine/scrapeheap

- Here's a useful guide: https://codewithkyrian.com/p/roachphp-mastering-web-scraping-with-php

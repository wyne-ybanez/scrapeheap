<?php

use Coderjerk\Scrapeheap\Scrapeheap;

require_once('bootstrap.php');

$target_url = $_POST['target_url'];

(new Scrapeheap)->scrape($target_url);

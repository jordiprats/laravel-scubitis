<?php

namespace App\Scrapers;

interface Scraper
{
  public function getWebPrice($url);
  public function createProductByURL($url);
}

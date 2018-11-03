<?php

namespace App\Scrapers;

interface Scraper
{
  public function getWebPriceByURL($url);
  public function createProductByURL($url);
}

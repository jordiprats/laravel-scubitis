<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebPrice;
use App\Scrapers\Scraper;
use App\Scrapers\CascoAntiguoScraper;

class WebPriceController extends Controller
{
  public static function getWebPriceByURL($url)
  {
    $scraper;
    switch ($url)
    {
      case (preg_match('/cascoantiguo.*/', $url) ? true : false) :
        $scraper = new CascoAntiguoScraper();
        break;
    }

    return $scraper->getWebPriceByURL($url);
  }
}

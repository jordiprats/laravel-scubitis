<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebPrice;
use App\Scrapers\Scraper;
use App\Scrapers\CascoAntiguoScraper;
use Carbon\Carbon;

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

  public static function createOrUpdate($url, $product_id, $price, $currency, $website)
  {
    $webprice = WebPrice::where([ 'url' => $url, 'product_id' => $product_id])->orderBy('data', 'DESC');
    if(($webprice) && ($webprice->price==$price) && ($webprice->currency == $currency))
    {
      $webprice->data=Carbon::now();

      $webprice->save();
    }
    else
    {
      $webprice = WebPrice::create([
        'url'        => $url,
        'price'      => $price,
        'currency'   => $currency,
        'product_id' => $product_id,
        'website'    => $website,
        'data'       => Carbon::now(),
      ]);
    }


    return $webprice;
  }
}

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
    $last_webprices = WebPrice::where([ 'url' => $url, 'product_id' => $product_id])->orderBy('data', 'DESC')->limit(2)->get();
    $last_webprices_count = $last_webprices->count();

    if(
      ($last_webprices_count==2) &&
      ($last_webprices->first()->price==$last_webprices->last()->price) &&
      ($last_webprices->first()->currency == $last_webprices->last()->currency) &&
      ($last_webprices->first()->price==$price) &&
      ($last_webprices->first()->currency==$currency)
      )
    {
      $last_webprices->first()->data=Carbon::now();

      $last_webprices->first()->save();
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

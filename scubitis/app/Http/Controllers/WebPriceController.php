<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebPrice;
use App\Scrapers\WebScraper;
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

  public static function createOrUpdateProductByURL($product_id, $url)
  {
    $scraper = WebScraper::getWebScraper($url);

    $product_data = $scraper->productDataArrayByURL($url);

    $webprice = WebPriceController::createOrUpdate($url, $product_id, $product_data['price'], $product_data['currency'], $product_data['website']);

    return $webprice;
  }

  public static function createOrUpdate($url, $product_id, $price, $currency, $website)
  {
    $last_webprices = WebPrice::where([ 'url' => $url, 'product_id' => $product_id])->orderBy('data', 'DESC')->limit(2)->get();
    $last_webprices_count = $last_webprices->count();

    if(
      ($last_webprices_count==2) &&
      ($last_webprices->first()->price==$last_webprices->last()->price) &&
      ($last_webprices->first()->currency == $last_webprices->last()->currency) &&
      ($last_webprices->first()->price==doubleval($price)) &&
      ($last_webprices->first()->currency==$currency)
      )
    {
      $webprice = $last_webprices->first();
      $webprice->data=Carbon::now();

      $webprice->save();
    }
    else
    {
      $webprice = WebPrice::create([
        'url'        => $url,
        'price'      => doubleval($price),
        'currency'   => $currency,
        'product_id' => $product_id,
        'website'    => $website,
        'data'       => Carbon::now(),
      ]);
    }


    return $webprice;
  }
}

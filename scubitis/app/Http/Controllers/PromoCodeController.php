<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scrapers\WebScraper;
use App\PromoCode;

class PromoCodeController extends Controller
{
  public static function createOrUpdatePromoCodeByURL($url)
  {
    $scraper = WebScraper::getWebScraper($url);

    $promo_code_data = $scraper->getPromoCode($url);

    if($promo_code_data!=null)
    {
      $promo_code = PromoCode::where(['promo_id' => $promo_code_data['promo_id'], 'website' => $promo_code_data['website']])->first();

      if(!$promo_code)
      {
        $discount = isset($promo_code_data['discount'])?$promo_code_data['discount']:null;
        $promo_code = PromoCode::create([
          'promo_id' => $promo_code_data['promo_id'],
          'website'  => $promo_code_data['website'],
          'discount' => $discount,
        ]);
      }
      return $promo_code;
    }
    return null;
  }
}

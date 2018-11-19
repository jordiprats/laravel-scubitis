<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
  public function createOrUpdatePromoCodeByURL($url)
  {
    $scraper = WebScraper::getWebScraper($url);

    $promo_codes = $scraper->getPromoCodes($url);

    foreach($promo_codes as $promo_code_data)
    {
      $promo_code = PromoCode::where(['promo_id' => $promo_code_data['promo_id'], 'website' => $promo_code_data['website']])->first();

      if(!$promo_code)
      {
        $promo_code = PromoCode::create([
          'promo_id' => $promo_code_data['promo_id'],
          'website'  => $promo_code_data['website'],
          'discount' => $promo_code_data['discount'],
        ]);
      }
      return $promo_code;
    }
  }
}

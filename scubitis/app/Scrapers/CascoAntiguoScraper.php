<?php

namespace App\Scrapers;

use Illuminate\Support\Facades\Log;

class CascoAntiguoScraper extends WebScraper
{
  public $cached_products = array();
  public $website_name = 'CascoAntiguo';

  public function getPromoCode($url)
  {
    $html=parent::getHTMLByURL($url);
    $promo_code_data = array();

    //product-cover
    // <script>
    // $('div.product-cover')
    // .append("<span style='text-align:left;display: inline-block; position: absolute; left: 6px; bottom: 6px;'><span style=';color:;font-family:Arial;font-size:9px;'><img style=\"box-shadow:unset;width:120%\" src='/img/stickers/8/stickerblackgrande.gif' /> </span></span>");
    // </script>

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument();
    $dom->loadHTML($html);

    $scripts = $dom->getElementsByTagName('script');
    foreach ($scripts as $script)
    {
      $data_script = $dom->saveXML($script, LIBXML_NOEMPTYTAG);
      //Log::info($data_script);

      if(
          (preg_match('/div\.product-cover/', $data_script)) &&
          (preg_match('/\/img\/stickers\/8\/stickerblackgrande.gif/', $data_script))
        )
        {
          //Log::info('found');
          $promo_code_data['website'] = $this->website_name;
          $promo_code_data['promo_id'] = 'unknown promo';

          $dom = new \DOMDocument();
          $dom->loadHTML($html);

          $spans = $dom->getElementsByTagName('span');
          foreach ($spans as $span)
          {

            if($span->getAttribute('class')=='textonaunciomercadillo')
            {
              $promo_id_raw = strip_tags($dom->saveXML($span, LIBXML_NOEMPTYTAG));
              $promo_id = preg_replace('/[^a-zA-Z0-9? ><;,{}[\]\-\/_+=!@#$:%\.\^&*|\']*/', '', $promo_id_raw);
              $promo_code_data['promo_id'] = $promo_id;

              //Log::info($promo_id);
              libxml_use_internal_errors(false);
              return $promo_code_data;
            }
          }
        }
    }

    libxml_use_internal_errors(false);
    return null;
  }

  public function productDataArrayByURL($url)
  {
    if(!isset($cached_products[$url]))
    {
      $product_data = array();

      $html=parent::getHTMLByURL($url);

      libxml_use_internal_errors(true);
      $dom = new \DOMDocument();
      $dom->loadHTML($html);

      $metas = $dom->getElementsByTagName('meta');
      foreach ($metas as $meta)
      {
        // <meta property="og:title" content="XS COMPACT PRO MC9 SC REGULATOR » Buy Online | Casco Antiguo Shop">
        if($meta->getAttribute('property')=='og:title')
        {
          preg_match('/^[a-zA-Z0-9? ><;,{}[\]\-\/_+=!@#$%\.\^&*|\']*/', $meta->getAttribute('content'), $content);
          $product_data['title'] = trim($content[0]);
        }

        // <meta property="og:description" content="The MC9-SEAL CHAMBER 1ST stage is environmentally sealed, protecting it against icing in cold water and against contamination from particulate matter in silty conditions.">
        if($meta->getAttribute('property')=='og:description')
          $product_data['description'] = $meta->getAttribute('content');

        //<meta property="og:image" content="https://www.cascoantiguo.com/22357-large_default/xs-compact-pro-mc9-sc-regulator.jpg">
        if($meta->getAttribute('property')=='og:image')
          $product_data['image_url'] = $meta->getAttribute('content');

        // <meta property="product:pretax_price:amount" content="163.636364">
        // <meta property="product:price:amount" content="198">
        if($meta->getAttribute('property')=='product:price:amount')
          $product_data['price'] = $meta->getAttribute('content');

        //<meta property="product:price:currency" content="EUR">
        if($meta->getAttribute('property')=='product:price:currency')
          $product_data['currency'] = $meta->getAttribute('content');
      }

      libxml_use_internal_errors(false);

      //https://www.cascoantiguo.com/en/scuba/fins/sport-pro-fin-subacqua
      $tokenized_url = explode("/", $url);
      $product_data['category_name'] = $tokenized_url[5];

      $product_data['website'] = $this->website_name;

      $cached_products[$url] = $product_data;
    }

    return $cached_products[$url];
  }
}

<?php

namespace App\Scrapers;

class CascoAntiguoScraper extends WebScraper
{
  public $cached_products = array();
  public $website_name = 'CascoAntiguo';

  public function getPromoCode($url)
  {
    $html=parent::getHTMLByURL($url);

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument();
    $dom->loadHTML($html);

    $spans = $dom->getElementsByTagName('span');
    foreach ($spans as $span)
    {
      if($span->getAttribute('class')=='textonaunciomercadillo')
      {
        $promo_code_data = array();
        $promo_code_data['promo_id'] = strip_tags($dom->saveXML($div, LIBXML_NOEMPTYTAG));
        $promo_code_data['website'] = $this->website_name;

        return $promo_code_data;
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

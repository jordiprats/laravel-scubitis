<?php

namespace App\Scrapers;

class CascoAntiguoScraper extends WebScraper
{

  public function createProductByURL($url)
  {
    $title = "";
    $description = "";
    // <meta property="og:url" content="https://www.cascoantiguo.com/en/scuba/diving-regulators/xs-compact-pro-mc9-sc-regulator-cressi">
    // <meta property="og:title" content="XS COMPACT PRO MC9 SC REGULATOR » Buy Online | Casco Antiguo Shop">
    // <meta property="og:description" content="The MC9-SEAL CHAMBER 1ST stage is environmentally sealed, protecting it against icing in cold water and against contamination from particulate matter in silty conditions.">
  }

  public function getWebPriceByURL($url)
  {
    $price="";

    $html=parent::getHTMLByURL($url);

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument();
    $dom->loadHTML($html);

    $metas = $dom->getElementsByTagName('meta');
    foreach ($metas as $meta)
    {
      // <meta property="product:pretax_price:amount" content="163.636364">
      // <meta property="product:price:amount" content="198">
      if($meta->getAttribute('property')=='product:price:amount')
      {
        $price = $meta->getAttribute('content');
      }
    }

    libxml_use_internal_errors(false);

    return $price;
  }
}

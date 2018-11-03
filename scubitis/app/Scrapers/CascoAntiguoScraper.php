<?php

namespace App\Scrapers;

class CascoAntiguoScraper implements Scraper
{
  public function createProductByURL($url)
  {
    $title = "";
    $description = "";
    // <meta property="og:url" content="https://www.cascoantiguo.com/en/scuba/diving-regulators/xs-compact-pro-mc9-sc-regulator-cressi">
    // <meta property="og:title" content="XS COMPACT PRO MC9 SC REGULATOR » Buy Online | Casco Antiguo Shop">
    // <meta property="og:description" content="The MC9-SEAL CHAMBER 1ST stage is environmentally sealed, protecting it against icing in cold water and against contamination from particulate matter in silty conditions.">
  }

  public function getWebPrice($url)
  {
    $price="";

    $c = curl_init($url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0');
    //curl_setopt(... other options you want...)

    $html = curl_exec($c);

    if (curl_error($c))
        die(curl_error($c));

    // Get the status code
    $status = curl_getinfo($c, CURLINFO_HTTP_CODE);

    curl_close($c);

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

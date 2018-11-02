<?php

namespace App\Scrapers;

class CascoAntiguoScraper implements Scraper
{
  public function getWebPrice($url)
  {
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

    libxml_use_internal_errors(false);

    return print_r($metas, true);
  }
}

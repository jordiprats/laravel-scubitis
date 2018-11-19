<?php

namespace App\Scrapers;

abstract class WebScraper
{
  public abstract function productDataArrayByURL($url);

  public $cached_urls = array();

  public static function getWebScraper($url)
  {
    switch ($url)
    {
      case (preg_match('/cascoantiguo.*/', $url) ? true : false) :
        return new CascoAntiguoScraper();
        break;
      case (preg_match('/scubastore.*/', $url) ? true : false) :
        return new ScubaStoreScraper();
        break;
    }
  }

  public function getHTMLByURL($url)
  {
    if(!isset($cached_urls[$url]))
    {
      $c = curl_init($url);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($c, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
      //curl_setopt(... other options you want...)

      $html = curl_exec($c);

      if (curl_error($c))
          die(curl_error($c));

      // Get the status code
      $status = curl_getinfo($c, CURLINFO_HTTP_CODE);

      curl_close($c);

      $cached_urls[$url] = $html;
    }

    return $cached_urls[$url];
  }
}

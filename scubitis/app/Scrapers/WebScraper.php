<?php

namespace App\Scrapers;

use Illuminate\Support\Facades\Log;

abstract class WebScraper
{
  public abstract function productDataArrayByURL($url);
  public abstract function getPromoCodes($url);

  public $cached_urls = array();

  public $user_agents = [
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; rv:50.0) Gecko/20100101 Firefox/50.0',
    'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0',
    'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/602.3.12 (KHTML, like Gecko) Version/10.0.2 Safari/602.3.12',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36'
  ];

  public function getRandomUserAgent()
  {
    return array_rand($user_agents, 1);
  }

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
      $user_agent = getRandomUserAgent();
      Log::info($user_agent);
      $c = curl_init($url);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($c, CURLOPT_USERAGENT, $user_agent);
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

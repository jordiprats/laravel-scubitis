<?php

namespace App\Scrapers;

//TODO - skel

class ScubaStoreScraper extends WebScraper
{
  public $cached_products = array();

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
        //<meta name="title" content="Cressi Guantes 3.5 mm Ultrastrecht Negro, Scubastore" />
        if($meta->getAttribute('property')=='og:title')
        {
          preg_match('/^[a-zA-Z0-9? ><;,{}[\]\-\/_+=!@#$%\.\^&*|\']*/', $meta->getAttribute('content'), $content);
          $product_data['title'] = trim($content[0]);
        }

        //<meta name="description" content="Cressi Guantes 3.5 mm Ultrastrecht - Negro.Modelo en el que prima su máxima elasticidad, fabricado en neopreno de baja densidad y forro en , buceo"/>
        if($meta->getAttribute('property')=='og:description')
          $product_data['description'] = $meta->getAttribute('content');



        //TODO
        if($meta->getAttribute('property')=='og:image')
          $product_data['image_url'] = $meta->getAttribute('content');

          // <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
  				// 	<meta itemprop="sku" content="74558" />
  				// 	<meta itemprop="priceCurrency" content="EUR" />
  				// 	<meta itemprop="price" content="17.95" />
  				// 	<meta itemprop="seller" content="scubastore" />
  				// 	<link itemprop="itemCondition" href="http://schema.org/NewCondition"/>
  				// 	<link itemprop="availability" href="http://schema.org/InStock"/>
  				// </span>



        if($meta->getAttribute('property')=='product:price:amount')
          $product_data['price'] = $meta->getAttribute('content');

        if($meta->getAttribute('property')=='product:price:currency')
          $product_data['currency'] = $meta->getAttribute('content');
      }

      libxml_use_internal_errors(false);

      //TODO
      $tokenized_url = explode("/", $url);
      $product_data['category_name'] = $tokenized_url[5];

      $product_data['website'] = 'scubastore - diveinn';

      $cached_products[$url] = $product_data;
    }

    return $cached_products[$url];
  }
}

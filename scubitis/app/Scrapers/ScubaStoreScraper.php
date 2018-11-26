<?php

namespace App\Scrapers;

use Illuminate\Support\Facades\Log;

class ScubaStoreScraper extends WebScraper
{
  public $cached_products = array();
  public $website_name = 'scubastore';

  public function getPromoCode($url)
  {
    $html=parent::getHTMLByURL($url);

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument();
    $dom->loadHTML($html);

    $divs = $dom->getElementsByTagName('div');
    foreach ($divs as $div)
    {
      //<div class="barra_black_friday" style="display:block" ><p><strong>BLACK FRIDAY -15% Code:BF2018</strong></p></div>
      if($div->getAttribute('class')=='barra_black_friday')
      {
        $promo_code_data = array();
        $promo_id_raw = strip_tags($dom->saveXML($div, LIBXML_NOEMPTYTAG));
        $promo_id = preg_replace('/[^a-zA-Z0-9? ><;,{}[\]\-\/_+=!@#$:%\.\^&*|\']*/', '', $promo_id_raw);
        $promo_code_data['promo_id'] = $promo_id;
        $promo_code_data['website'] = $this->website_name;

        $dom_spans = new \DOMDocument();
        $dom_spans->loadHTML($html);
        $spans = $dom_spans->getElementsByTagName('span');
        Log::info(count($spans));
        foreach ($spans as $span)
        {
          if($span->getAttribute('id')=='precio_anterior')
          {
            //<span id="precio_anterior">20€</span>
            //<span id="precio_anterior">€</span>
            //si ja te descompte no aplica
            $preu_anterior = strip_tags($dom_spans->saveXML($span, LIBXML_NOEMPTYTAG));

            Log::info($preu_anterior);

            if(preg_match('/[0-9]/', $preu_anterior))
            {
              libxml_use_internal_errors(false);
              return null;
            }
          }
        }

        libxml_use_internal_errors(false);
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
        //<meta name="title" content="Cressi Guantes 3.5 mm Ultrastrecht Negro, Scubastore" />
        if($meta->getAttribute('property')=='og:title')
        {
          preg_match('/^[a-zA-Z0-9? ><;,{}[\]\-\/_+=!@#$%\.\^&*|\']*/', $meta->getAttribute('content'), $content);
          $product_data['title'] = trim($content[0]);
        }

        //<meta name="description" content="Cressi Guantes 3.5 mm Ultrastrecht - Negro.Modelo en el que prima su máxima elasticidad, fabricado en neopreno de baja densidad y forro en , buceo"/>
        if($meta->getAttribute('name')=='description')
          $product_data['description'] = $meta->getAttribute('content');

        //TODO - imatge
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

        if($meta->getAttribute('itemprop')=='price')
          $product_data['price'] = $meta->getAttribute('content');

        if($meta->getAttribute('itemprop')=='priceCurrency')
          $product_data['currency'] = $meta->getAttribute('content');

        //TODO - altres
        // <meta itemprop="brand" content="Aqualung">
				// <meta itemprop="name" content="Aqualung Legend LX Supreme ACD DIN">


      }

      $product_data['website'] = $this->website_name;

      $product_data['available']=isset($product_data['price']);

      $cached_products[$url] = $product_data;
    }

    $inputs = $dom->getElementsByTagName('input');
    foreach ($inputs as $input)
    {
      //TODO - categoria
      // <input type="hidden" name="category" id="category" value="regulators / regulators set" data-ta-layer="category"/>
      // <input type="hidden" name="subcategory" id="subcategory" value="regulators set" data-ta-layer="subcategory"/>
      if($input->getAttribute('name')=='subcategory')
        $product_data['category_name'] = $input->getAttribute('value');

      // preu producte no disponible
      // <input type="hidden" name="productFinalPrice" id="productFinalPrice" value="454.95" data-ta-layer="productFinalPrice"/>
      if($input->getAttribute('name')=='productFinalPrice')
        if(!$product_data['available'])
          $product_data['price'] = $input->getAttribute('value');

    }

    libxml_use_internal_errors(false);
    return $cached_products[$url];
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\WebPrice;
use App\Scrapers\Scraper;
use App\Scrapers\CascoAntiguoScraper;
use App\Charts\WebPricesChart;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    $products=Product::paginate(50);
    return view('products.list',compact('products'))->with('i', ($request->input('page', 1) - 1) * 50);
  }

  public function show($id)
  {
    $product = Product::findOrFail($id);

    $chart = new WebPricesChart;
    $chart->height(500);
    $chart->labels($product->webprices->pluck('data'));
    foreach(WebPrice::distinct()->select('website')->where('product_id', '=', $product->id)->groupBy('website')->get() as $website)
    {
      Log::info($website->website);
      $chart->dataset($website->website, 'line', WebPrice::where([ ['product_id', '=', $id], ['website', '=', $website->website]])->get()->pluck('price'));
    }

    return view('products.show')
            ->with('product', $product)
            ->with('chart', $chart);
  }

  public static function createOrUpdateProductByURL($url)
  {
    $scraper;
    switch ($url)
    {
      case (preg_match('/cascoantiguo.*/', $url) ? true : false) :
        $scraper = new CascoAntiguoScraper();
        break;
    }

    $product_data = $scraper->productDataArrayByURL($url);

    $category = CategoryController::createOrUpdate($product_data['category_name']);

    $product = ProductController::createOrUpdate($product_data['title'], $product_data['description'], $category->id, $product_data['image_url']);

    $webprice = WebPriceController::createOrUpdate($url, $product->id, $product_data['price'], $product_data['currency'], $product_data['website']);

    return $product;
  }

  public static function toStrCmp(string $string)
  {
    $conectors = array('-', ',');

    $output = strtolower($string);
    $output = preg_replace('/\b('.implode('|',$conectors).')\b/','',$output);
    $output = trim(preg_replace('/\s+/', '', str_replace("\n", "", $output)));
    $output = str_slug($output, '');

    return $output;
  }

  public static function createOrUpdate(string $title, string $description = null, $category_id = null, $image_url = null)
  {
    $product = Product::where(['title_strcmp' => ProductController::toStrCmp($title)])->first();

    if(!$product)
    {
      $product = Product::create([
        'title'        => $title,
        'title_strcmp' => ProductController::toStrCmp($title),
        'description'  => $description,
        'image_url'    => $image_url,
        'category_id'  => $category_id,
      ]);
    }
    else
    {
      $product->description = $description;
      $product->category_id = $category_id;

      $product->save();
    }

    return $product;
  }
}

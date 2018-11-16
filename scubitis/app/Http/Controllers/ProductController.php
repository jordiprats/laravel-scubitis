<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\WebPrice;
use App\Scrapers\Scraper;
use App\Scrapers\WebScraper;
use App\Charts\WebPricesChart;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

  public function autocomplete(Request $request)
  {
    $data = Product::search($request->input('query'))->get();
    return response()->json($data);
  }

  public function search(Request $request)
  {
    $products = Product::search($request->input('q'))->paginate(10);
    return view('products.list',compact('products'))->with('i', ($request->input('page', 1) - 1) * 50);
  }

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

    Log::info(WebPrice::distinct()->where([['product_id', '=', $id]])->orderBy('data')->get()->pluck('data'));
    //$chart->labels(WebPrice::distinct()->where([['product_id', '=', $id]])->orderBy('data')->get()->pluck('data'));
    foreach(WebPrice::distinct()->select('website')->where('product_id', '=', $product->id)->groupBy('website')->get() as $website)
    {
      Log::info(WebPrice::where([ ['product_id', '=', $id], ['website', '=', $website->website]])->get()->pluck('price', 'data')->toArray());

      $chart->dataset($website->website, 'line', WebPrice::where([ ['product_id', '=', $id], ['website', '=', $website->website]])->get()->pluck('price', 'data')->toArray());
    }

    return view('products.show')
            ->with('product', $product)
            ->with('chart', $chart);
  }

  public static function createOrUpdateProductByURL($url)
  {
    $scraper = WebScraper::getWebScraper($url);

    $product_data = $scraper->productDataArrayByURL($url);

    $related_webprice = WebPrice::where(['url' => $url])->first();
    if($related_webprice)
    {
      $product = $related_webprice->product;

      $category = $product->category;
    }
    else
    {
      if(isset($product_data['title']) && isset($product_data['description']) && $product_data['category_name'])
      {
        $category = CategoryController::createOrUpdate($product_data['category_name']);

        $product = ProductController::createOrUpdate($product_data['title'], $product_data['description'], $category->id, $product_data['image_url']);
      }
      else return null;

    }

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

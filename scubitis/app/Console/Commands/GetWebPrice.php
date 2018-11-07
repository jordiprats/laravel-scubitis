<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WebPriceController;
use App\Http\Controllers\ProductController;
use App\WebPrice;
use App\Product;
use Carbon\Carbon;

class GetWebPrice extends Command
{
  protected $signature = 'scubitis:getwebprice {title} {url}';
  protected $description = 'get web price';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $title=$this->argument('title');
    $url=$this->argument('url');

    $productDataArray = WebPriceController::productDataArrayByURL($url);

    $product = Product::where(['title_strcmp' => ProductController::toStrCmp($title)])->first();

    if(!$product)
      die("product NOT FOUND");

    $webprice = WebPrice::create([
      'url'        => $url,
      'price'      => $productDataArray['price'],
      'currency'   => $productDataArray['currency'],
      'product_id' => $product->id,
      'data'       => Carbon::now(),
    ]);

    print("product: ".$product->title."\n");
    print("url: ".$webprice->url."\n");
    print("timestamp: ".$webprice->data->diffForHumans()."\n");
    print("price: ".$webprice->price." ".$webprice->currency."\n");
  }
}

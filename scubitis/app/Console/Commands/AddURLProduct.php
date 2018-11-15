<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WebPriceController;

class AddURLProduct extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scubitis:addurlproduct {product_id} {url}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'add alternate URL to a given product';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $product_id=$this->argument('product_id');
    $url=$this->argument('url');

    $webprice = WebPriceController::createOrUpdateProductByURL($product_id, $url);

    if($webprice)
    {
      print($webprice->product->title." update with webprice from ".$webprice->website."\n");
    }
  }
}

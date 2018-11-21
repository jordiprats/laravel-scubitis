<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;

class GetLatestWebPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scubitis:getlatestwebprices {product_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get latest web prices by product_id';

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

      $product = Product::findOrFail($product_id);

      print($product->title.":\n");
      foreach($product->latestwebprices as $webprice)
      {
        print($webprice->website.": ".$webprice->price." ".$webprice->data."\n");
      }
    }
}

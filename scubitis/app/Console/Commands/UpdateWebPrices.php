<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;
use App\WebPrice;
use App\Http\Controllers\ProductController;

class UpdateWebPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scubitis:updateprices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product\'s prices';

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
      foreach(WebPrice::distinct()->get(['product_id']) as $product_amb_webprice)
      {
        $product = Product::where(['id' => $product_amb_webprice->product_id])->first();

        if($product)
        {
          //WebPrice::where(['id' => $product_amb_webprice->product_id])
          foreach(WebPrice::distinct()->select('url')->where('product_id', '=', $product->id)->groupBy('url')->get() as $webprice)
          {
            $product = ProductController::createOrUpdateProductByURL($webprice->url);
            if($product)
            {
              print($product->title." -  webprice succesfully updated\n");
            }
          }

        }
      }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ProductController;
use App\Product;

class CreateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scubitis:createproduct {title}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create product';

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
      $title=$this->argument('title');
      $product = Product::where(['title_strcmp' => ProductController::toStrCmp($title)])->first();

      if(!$product)
      {
        $product = Product::create([
          'title'        => $title,
          'title_strcmp' => ProductController::toStrCmp($title),
        ]);
      }
      else
      {
        print("== product already exists:\n");
      }

      print("\ttitle: ".$product->title."\n");
      print("\tdescription: ".$product->description."\n");
    }
}

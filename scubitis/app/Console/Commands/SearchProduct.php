<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;

class SearchProduct extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scubitis:searchproduct {search}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'search for products';

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
    foreach(Product::search($this->argument('search'))->get() as $product)
    {
      print("[".$product->id."]: ".$product->title."\n");
    }
  }
}

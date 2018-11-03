<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WebPriceController;

class GetWebPrice extends Command
{
  protected $signature = 'scubitis:getwebprice {url}';
  protected $description = 'get web price';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $url=$this->argument('url');

    print(WebPriceController::getWebPriceByURL($url));
  }
}

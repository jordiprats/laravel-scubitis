<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PromoCodeController;

class GetPromoCodesByURL extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scubitis:getpromocodesbyurl {url}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'get promotional codes by URL';

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
    $url=$this->argument('url');

    $promo_code = PromoCodeController::createOrUpdatePromoCodeByURL($url);

    if($promo_code)
    {
      print($promo_code->promo_id." - succesfully imported\n");
    }
    else
    {
      print("Unable to fetch product info based on this URL");
    }
  }
}

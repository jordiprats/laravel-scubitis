<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PromoCodeController;

class GetPromoCodeByURL extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'scubitis:getpromocodebyurl {url}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'get promotional code by URL';

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
      print("Unable to fetch promo info based on this URL");
    }
  }
}

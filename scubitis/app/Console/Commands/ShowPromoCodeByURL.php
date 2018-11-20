<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scrapers\WebScraper;

class ShowPromoCodeByURL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scubitis:showpromocodebyurl {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'show promocode by url';

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

      $scraper = WebScraper::getWebScraper($url);

      $promo_code_data = $scraper->getPromoCode($url);

      print_r($promo_code_data);
    }
}

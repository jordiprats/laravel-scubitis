<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CategoryController;
use App\Category;

class CreateCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scubitis:createcategory {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create category';

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
      $name=$this->argument('name');
      $category = Category::where(['name_strcmp' => CategoryController::toStrCmp($name)])->first();

      if(!$category)
      {
        $category = Category::create([
          'name'        => $name,
          'name_strcmp' => CategoryController::toStrCmp($name),
        ]);
      }
      else
      {
        print("== category already exists:\n");
      }

      print("\tname: ".$category->name."\n");
    }
}

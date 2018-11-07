<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
  public static function toStrCmp(string $string)
  {
    $conectors = array('-', ',');

    $output = strtolower($string);
    $output = preg_replace('/\b('.implode('|',$conectors).')\b/','',$output);
    $output = trim(preg_replace('/\s+/', '', str_replace("\n", "", $output)));
    $output = str_slug($output, '');

    return $output;
  }

  public static function createOrUpdate(string $name)
  {
    $category = Category::where(['name_strcmp' => CategoryController::toStrCmp($name)])->first();

    if(!$category)
    {
      $category = Category::create([
        'name'        => $name,
        'name_strcmp' => CategoryController::toStrCmp($name),
      ]);
    }
    return $category;
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
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

  public static function createOrUpdate(string $title, string $description = null, string $category_id = null)
  {
    $product = Product::where(['title_strcmp' => ProductController::toStrCmp($title)])->first();

    if(!$product)
    {
      $product = Product::create([
        'title'        => $title,
        'title_strcmp' => ProductController::toStrCmp($title),
        'description'  => $description,
        'category_id'  => $category_id,
      ]);
    }
    else
    {
      $product->description = $description;
      $product->category_id = $category_id;

      $product->save();
    }

    return $product;
  }
}

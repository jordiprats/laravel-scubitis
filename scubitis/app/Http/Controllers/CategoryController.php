<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}

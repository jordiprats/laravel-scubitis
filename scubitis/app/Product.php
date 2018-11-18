<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
  use Searchable;

  protected $guarded = [];

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function webprices()
  {
    return $this->hasMany(WebPrice::class);
  }

  public function getCurrentminwebpriceAttribute()
  {
    $numero_web_prices = WebPrice::distinct()->select('website')->where([ ['product_id', '=', $this->id] ])->count();
    return WebPrice::where([ ['product_id', '=', $this->id] ])->orderBy('data', 'DESC')->limit($numero_web_prices)->get()->sortBy('price')->first();
  }

  public function getMinwebpriceAttribute()
  {
    return $this->webprices->where('price', $this->webprices->min('price'))->sortByDesc('data')->first();
    //return $this->webprices->min('price');
  }

  public function getMaxwebpriceAttribute()
  {
    return $this->webprices->where('price', $this->webprices->max('price'))->sortByDesc('data')->first();
    //return $this->webprices->max('price');
  }

  public function getAveragepriceAttribute()
  {
    return $this->webprices->avg('price');
  }

  public function toSearchableArray()
  {
    $array = $this->toArray();

    //Index 'products' created with fields: `title`,`title_strcmp`,`description`,`image_url`
    return array('id' => $array['id'], 'title' => $array['title'], 'description' => $array['description']);
  }
}

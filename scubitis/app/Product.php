<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
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

  public function getGlobaldiscountAttribute()
  {
    return round(-100.0+((double)$this->minwebprice->price/(double)WebPrice::where([ ['product_id', '=', $this->id] ])->max('price'))*100.0, 2);
  }


  public function getLatestwebpricesAttribute()
  {
    $collection = collect();
    $websites= WebPrice::distinct()->select('website')->where([ ['product_id', '=', $this->id] ])->groupBy('website')->get();
    foreach($websites as $website)
    {
      $collection->push(WebPrice::where([ ['product_id', '=', $this->id], [ 'website', '=', $website->website ] ])->orderBy('data', 'DESC')->first());
    }
    return $collection->sortBy('price');
  }

  public function getCurrentminwebpriceAttribute()
  {
    return $this->latestwebprices->firstWhere('price', $this->latestwebprices->min('price'));
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
    return round(doubleval($this->webprices->avg('price')),2);
  }

  public function toSearchableArray()
  {
    $array = $this->toArray();

    //Index 'products' created with fields: `title`,`title_strcmp`,`description`,`image_url`
    return array('id' => $array['id'], 'title' => $array['title'], 'description' => $array['description']);
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $guarded = [];

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function webprices()
  {
    return $this->hasMany(WebPrice::class);
  }

  public function getMinpriceAttribute()
  {
    //return $this->webprices->where('price', $this->webprices->min('price'))->first();
    return $this->webprices->min('price');
  }

  public function getMaxpriceAttribute()
  {
    return $this->webprices->max('price');
  }

  public function getAveragepriceAttribute()
  {
    return $this->webprices->avg('price');
  }
}

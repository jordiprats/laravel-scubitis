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

  public function toSearchableArray()
  {
    $array = $this->toArray();

    //Index 'products' created with fields: `title`,`title_strcmp`,`description`,`image_url`
    return array('id' => $array['id'], 'title' => $array['title'], 'description' => $array['description']);
  }
}

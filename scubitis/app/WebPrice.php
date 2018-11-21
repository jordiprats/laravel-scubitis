<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebPrice extends Model
{
  protected $guarded = [];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function getDiscountAttribute()
  {
    return round(100.0-((double)$this->price/(double)WebPrice::where([ ['product_id', '=', $this->product_id], [ 'website', '=', $this->website ] ])->max('price'))*100.0, 2);
  }
}

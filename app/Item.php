<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'base_price'];

    public function pricingOptions()
    {
        return $this->belongsToMany(PricingOption::class);
    }
}

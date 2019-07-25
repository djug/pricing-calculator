<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PricingOption extends Model
{
    protected $fillable = ['name', 'tweak_class', 'tweak_parameter'];

    public function item()
    {
        return $this->belongsToMany(Item::class);
    }
}

<?php
namespace App\Tweaks;

use App\Tweaks\Traits\HasTweakParameter;
use App\Tweaks\Traits\HasTweakCondition;

/**
 * This tweak is applicable when a location based condition
 * is met and apply an increase (percentage) on the price
 */

class LocationBasedIncreasePercentage implements TweakInterface
{
    use HasTweakParameter;
    use HasTweakCondition;

    protected $currentLocation;

    public function __construct()
    {
        $this->currentLocation = getCurrentLocation();
    }

    public function tweak($price)
    {
        if (strtolower($this->currentLocation) == strtolower($this->condition)) {
            return $price * (100 + $this->parameter) / 100;
        }

        return $price;
    }
}

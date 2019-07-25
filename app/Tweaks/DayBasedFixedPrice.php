<?php
namespace App\Tweaks;
use App\Tweaks\Traits\HasTweakParameter;
use App\Tweaks\Traits\HasTweakCondition;
use Carbon\Carbon;

/**
 * This tweak is applicable when a day based condition
 * is met and return a fixed price
 */
class DayBasedFixedPrice implements TweakInterface
{
    use HasTweakParameter;
    use HasTweakCondition;

    protected $today;

    public function __construct()
    {
        $this->today = getToday();
    }

    public function tweak($price)
    {
        $methodName = "is".ucfirst($this->condition);

        if ($this->today->$methodName()) {
            return $this->parameter;
        }

        return $price;
    }
}

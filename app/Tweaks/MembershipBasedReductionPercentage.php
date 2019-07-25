<?php
namespace App\Tweaks;

use App\Tweaks\Traits\HasTweakParameter;
use App\Tweaks\Traits\HasTweakCondition;

/**
 * This tweak is applicable when a membership based condition
 * is met and apply a reduction (percentage) on the price
 */

class MembershipBasedReductionPercentage implements TweakInterface
{
    use HasTweakParameter;
    use HasTweakCondition;

    protected $userMemebership;

    public function __construct()
    {
        $this->userMemebership = getUserMemebership();
    }

    public function tweak($price)
    {
        if (strtolower($this->userMemebership) == strtolower($this->condition)) {
            return $price * (100 - $this->parameter) / 100;
        }

        return $price;
    }
}

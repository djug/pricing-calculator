<?php
namespace App\Tweaks\Traits;

trait HasTweakCondition
{
    protected $condition;

    public function setTweakCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }
}

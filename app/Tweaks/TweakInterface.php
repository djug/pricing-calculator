<?php
namespace App\Tweaks;

/**
 * All the tweaks should implement this interface
 */
interface TweakInterface
{
    public function setTweakCondition($condition);
    public function setTweakParameter($parameter);
    public function tweak($price);
}

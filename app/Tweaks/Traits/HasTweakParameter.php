<?php
namespace App\Tweaks\Traits;

trait HasTweakParameter
{
    protected $parameter;

    public function setTweakParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }
}

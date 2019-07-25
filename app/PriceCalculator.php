<?php
namespace App;

use App\Item;
use App\HasDebugMode;

class PriceCalculator
{
    use HasDebugMode;

    protected $item;
    protected $steps;

    /**
     * All that the calculator need is the Item
     * that we are going to run the calculations on
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
    /**
     * Each item has a series of pricing options
     * we loop over them one by one and an apply
     * the applicable tweaks
     */
    public function calculate()
    {
        $pricingOptions = $this->item->pricingOptions;
        $price = $this->item->base_price;

        foreach ($pricingOptions as $option) {
            $tweak = $this->getTweeker($option->tweak_class);

            $newPrice = $tweak
                        ->setTweakCondition($option->tweak_condition)
                        ->setTweakParameter($option->tweak_parameter)
                        ->tweak($price);

            $this->recordStep($option->name, $price, $newPrice);
            $price = $newPrice;
        }
        return $price;
    }

    /**
     * this method allows us to get the class
     * of the tweak
     */
    private function getTweeker($tweakClass)
    {
        $tweakClass = config('app.tweaks_base_path').$tweakClass;

        return new $tweakClass();
    }
}

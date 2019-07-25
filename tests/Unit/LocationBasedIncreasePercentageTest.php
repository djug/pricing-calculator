<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Item;
use App\PricingOption;
use App\PriceCalculator;

class LocationBasedIncreasePercentageTest extends TestCase
{
    use DatabaseTransactions;

    protected $item;

    public function setUp() : void
    {
        parent::setUp();

        $this->item = Item::create(['name' => 'Zumba', 'base_price' => 100]);

        $londonIncrease25percent = PricingOption::create([
            'name' => 'London increase 25 %',
            'tweak_class' => 'LocationBasedIncreasePercentage',
            'tweak_condition' => 'london',
            'tweak_parameter' => '25',
        ]);

        $this->item->pricingOptions()->attach([$londonIncrease25percent->id]);
    }

    /**
     * @test
     */
    public function it_applied_an_increase_on_the_price_when_the_location_condition_is_met()
    {
        setCurrentLocation("London");

        $calculator = new PriceCalculator($this->item);

        $price = $calculator->calculate();

        $this->assertEquals($this->item->base_price * 1.25, $price);
    }

    /**
     * @test
     */
    public function it_doesnt_apply_an_increase_on_the_price_when_the_location_condition_is_not_met()
    {
        setCurrentLocation("Paris");

        $calculator = new PriceCalculator($this->item);

        $price = $calculator->calculate();

        $this->assertEquals($this->item->base_price, $price);
    }
}

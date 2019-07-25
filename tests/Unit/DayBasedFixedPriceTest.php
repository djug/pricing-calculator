<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Item;
use App\PricingOption;
use App\PriceCalculator;

class DayBasedFixedPriceTest extends TestCase
{
    use DatabaseTransactions;

    protected $item;

    public function setUp() : void
    {
        parent::setUp();

        $this->item = Item::create(['name' => 'Zumba']);

        $weekdaysFixedPrice = PricingOption::create([
            'name' => 'Weekdays Fixed Price (£6)',
            'tweak_class' => 'DayBasedFixedPrice',
            'tweak_condition' => 'weekday',
            'tweak_parameter' => '6',
        ]);

        $this->item->pricingOptions()->attach([$weekdaysFixedPrice->id]);
    }

    /**
     * @test
     */
    public function it_applied_an_increase_on_the_price_when_the_day_condition_is_met()
    {
        $weekDay = "25-07-2019"; // this is a week day
        setToday($weekDay);

        $calculator = new PriceCalculator($this->item);

        $price = $calculator->calculate();

        $this->assertEquals(6, $price);
    }

    /**
     * @test
     */
    public function it_doesnt_apply_an_increase_on_the_price_when_the_day_condition_is_not_met()
    {
        $weekendDay = "27-07-2019"; // this is a weekend day
        setToday($weekendDay);

        $calculator = new PriceCalculator($this->item);

        $price = $calculator->calculate();

        // the price will stay unchanged
        $this->assertEquals($this->item->base_price, $price);
    }
}

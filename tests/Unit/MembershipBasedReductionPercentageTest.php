<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Item;
use App\PricingOption;
use App\PriceCalculator;

class MembershipBasedReductionPercentageTest extends TestCase
{
    use DatabaseTransactions;

    protected $item;

    public function setUp() : void
    {
        parent::setUp();

        $this->item = Item::create(['name' => 'Zumba', 'base_price' => 100]);

        $standarMembershipReduction20percent = PricingOption::create([
            'name' => 'Standard membership reduction 20 %',
            'tweak_class' => 'MembershipBasedReductionPercentage',
            'tweak_condition' => 'standard',
            'tweak_parameter' => '20',
        ]);

        $this->item->pricingOptions()->attach([$standarMembershipReduction20percent->id]);
    }

    /**
     * @test
     */
    public function it_applied_a_reduction_on_the_price_when_the_membership_condition_is_met()
    {
        setCurrentUserMemebership("standard");

        $calculator = new PriceCalculator($this->item);

        $price = $calculator->calculate();

        $this->assertEquals($this->item->base_price * 0.8 , $price);
    }

    /**
     * @test
     */
    public function it_doesnt_apply_a_reduction_on_the_price_when_the_membership_condition_is_not_met()
    {
        setCurrentUserMemebership("other");

        $calculator = new PriceCalculator($this->item);

        $price = $calculator->calculate();

        $this->assertEquals($this->item->base_price, $price);
    }
}

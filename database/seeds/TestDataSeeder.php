<?php

use Illuminate\Database\Seeder;
use App\Item;
use App\PricingOption;
use App\User;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        $zumba = Item::create(['name' => 'Zumba']);

        $weekdaysFixedPrice = PricingOption::create([
            'name' => 'Weekdays Fixed Price (£6)',
            'tweak_class' => 'DayBasedFixedPrice',
            'tweak_condition' => 'weekday',
            'tweak_parameter' => '6',
        ]);

        $weekendsFixedPrice = PricingOption::create([
            'name' => 'Weekends Fixed Price (£8)',
            'tweak_class' => 'DayBasedFixedPrice',
            'tweak_condition' => 'weekend',
            'tweak_parameter' => '8',
        ]);

        $londonIncrease25percent = PricingOption::create([
            'name' => 'London increase 25 %',
            'tweak_class' => 'LocationBasedIncreasePercentage',
            'tweak_condition' => 'london',
            'tweak_parameter' => '25',
        ]);

        $standardMembership = PricingOption::create([
            'name' => 'Standard membership reduction 50 %',
            'tweak_class' => 'MembershipBasedReductionPercentage',
            'tweak_condition' => 'standard',
            'tweak_parameter' => '50',
        ]);

        $premiumMembership = PricingOption::create([
            'name' => 'Premium membership reduction 100 %',
            'tweak_class' => 'MembershipBasedReductionPercentage',
            'tweak_condition' => 'premium',
            'tweak_parameter' => '100',
        ]);

        $zumba->pricingOptions()->attach([
            $weekdaysFixedPrice->id,
            $weekendsFixedPrice->id,
            $londonIncrease25percent->id,
            $standardMembership->id,
            $premiumMembership->id,
        ]);
    }
}

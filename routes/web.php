<?php

Route::get('/', function () {
    return view('welcome');
});
use App\Item;
use App\PriceCalculator;


Route::get('/demo', function () {
    $item = Item::find(1);

    $calculator = new PriceCalculator($item);

    $price = $calculator->debugMode(true)->calculate();
    print "Final Price: ".$price."<br>";

    $steps = $calculator->getDebugStack();

    dd($steps);
});

<?php
use Carbon\Carbon;

function setCurrentLocation($city)
{
    putenv("CURRENT_CITY=".$city);
}

function getCurrentLocation()
{
    return env('CURRENT_CITY', 'London');
}


function setCurrentUserMemebership($mebership)
{
    putenv("CURRENT_MEMBERSHIP=".$mebership);
}

function getCurrentUserMemebership()
{
    return env('CURRENT_MEMBERSHIP', 'standard');
}


function setToday($date)
{
    putenv("TODAY=".$date);
}
function getToday()
{
    return env('TODAY') ? Carbon::parse(env('TODAY')) : Carbon::today();
}

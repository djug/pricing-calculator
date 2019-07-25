<?php

function getCurrentLocation()
{
    return env('CURRENT_CITY', 'London');
}

function getUserMemebership()
{
    return env('MEMBERSHIP', 'standard');
}

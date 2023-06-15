<?php

use Illuminate\Support\Carbon;

function dateStringToHumanNL($date): string
{
    return $date ? $date->locale('nl_NL')->isoFormat('DD MMMM YYYY') : 'onbekend';
}

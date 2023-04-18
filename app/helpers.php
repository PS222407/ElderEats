<?php

use Illuminate\Support\Carbon;

function dateStringToHumanNL($date): string
{
    return $date ? Carbon::createFromFormat('Y-m-d', $date, 'UTC')->locale('nl_NL')->isoFormat('DD MMMM YYYY') : 'onbekend';
}

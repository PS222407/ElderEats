<?php

use Illuminate\Support\Carbon;

function dateStringToHumanNL($date): string
{
    if (is_string($date)) {
        return $date ? Carbon::create($date)->locale('nl_NL')->isoFormat('DD MMMM YYYY') : 'onbekend';
    }

    return $date ? $date->locale('nl_NL')->isoFormat('DD MMMM YYYY') : 'onbekend';
}

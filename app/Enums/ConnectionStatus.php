<?php

namespace App\Enums;

enum ConnectionStatus: int
{
    case CONNECTED = 0;
    case IN_PROCESS = 1;
    case INACTIVE = 2;
}

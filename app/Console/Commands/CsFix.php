<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CsFix extends Command
{
    protected $signature = 'cs:fix';

    protected $description = 'run php-cs-fixer';

    public function handle(): void
    {
        shell_exec(base_path().'/vendor/bin/php-cs-fixer fix --allow-risky=yes');
    }
}

<?php

namespace App\Listeners;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Log;

class CommandFinishedListener
{
    public function handle(CommandFinished $event) {
        global $argv;
        $executeCommand = 'php '. implode(' ', $argv);
        Log::channel('cli')->info($executeCommand);
    }
}

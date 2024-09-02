<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogTransaction implements ShouldQueue
{
    public function handle(TransactionCreated $event)
    {
        Log::info('New transaction created', ['transaction' => $event->transaction]);
    }
}
<?php

namespace App\Console\Commands;

use App\Models\Logs;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Log;

class DeleteOldLogs extends Command
{
    protected $signature = 'logs:delete-old';
    protected $description = 'Delete logs older than one month';

    public function handle()
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        Logs::where('created_at', '<', $oneMonthAgo)->delete();

        $this->info('Old logs have been deleted.');
    }
}

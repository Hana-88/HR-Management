<?php

namespace App\Providers;

use App\Models\Logs;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class DatabaseLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Logging\DatabaseLogger', function () {
        return new DatabaseLogger();
        });
    }
}

class DatabaseLogger extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {

        $logData = [
        'channel' => $record['channel'],
        'level' => $record['level'],
        'message' => $record['message'],
        'context' => json_encode($record['context']),
        'created_at' => $record['datetime']->format('Y-m-d H:i:s'),
        ];
        Logs::create($logData);
    }
}

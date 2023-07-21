<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveLogs extends Command
{
    protected $signature = 'logs:remove {extension=log : The file extension of the logs to remove}';

    protected $description = 'Remove log files from the specified directory with a given extension';

    public function handle()
    {
        $directory = $this->ask('Enter the path of the directory containing log files:');
        $extension = $this->argument('extension');

        if (!is_dir($directory)) {
            $this->error("Directory not found: {$directory}");
            return;
        }

        $files = File::glob("{$directory}/*.{$extension}");

        if (empty($files)) {
            $this->info("No log files with the extension .{$extension} found in {$directory}");
            return;
        }

        foreach ($files as $file) {
            File::delete($file);
            $this->info("Deleted: {$file}");
        }

        $this->info("Log files with the extension .{$extension} have been removed from {$directory}");
    }
}

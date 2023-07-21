<?php

namespace App\Console\Commands;

use App\Models\Employees;
use Illuminate\Console\Command;
use App\Models\Employee; // Replace with the appropriate namespace for your Employee model

class ExportEmployees extends Command
{
    protected $signature = 'export:employees';
    protected $description = 'Export all employees to a JSON file';

    public function handle()
    {
        $employees = Employees::all();

        $filename = 'employees.json';
        $jsonString = json_encode($employees, JSON_PRETTY_PRINT);

        file_put_contents($filename, $jsonString);

        $this->info('All employees exported to employees.json');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Employees;
use App\Models\Logs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Employees::factory(10)->create();
//        Logs::factory(20)->create();
        // Add more factories here if you have additional ones or need more data.
    }
}

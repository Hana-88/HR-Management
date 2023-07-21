<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;

class InsertFakeEmployees extends Command
{
    protected $signature = 'db:insert_fake_employees';
    protected $description = 'Insert 1000 rows of fake data into the employees table';

    public function handle()
    {
        $faker = Faker::create();

        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                'name' => $faker->name,
                'age' => $faker->numberBetween(20, 60),
                'salary' => $faker->numberBetween(30000, 100000),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'hired_date' => $faker->date,
                'job_title' => $faker->jobTitle,
                'manager_id' => null,
            ];
        }

        \DB::table('employees')->insert($data);

        $this->info('1000 rows of fake employees data inserted successfully.');
    }
}

<?php

namespace App\Jobs;

use App\Models\Employees;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportEmployeesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * Create a new job instance.
     *
     * @param  UploadedFile  $file
     * @return void
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $validator = Validator::make(['csv_file' => $this->file], [
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return;
        }

        $csvData = array_map('str_getcsv', file($this->file));

        foreach ($csvData as $row) {
            Employees::create([
                'name' => $row[0],
                'age' => $row[1],
                'salary' => $row[2],
                'gender' => $row[3],
                'hired_date' => $row[4],
                'job_title' => $row[5],
                'manager_id' => $row[6],
            ]);
        }
    }
}

<?php


namespace App\Http\Controllers;

use App\Jobs\ImportEmployeesJob;
use Database\Seeders\EmployeesSeeder;
use Illuminate\Support\Facades\Log;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\Driver\Manager;
use App\Http\Controllers\Controller;


class EmployeesController extends Controller
{





    public function index()
    {
        Log::channel('employee_log')->info("All employees are retrieved");
        return Employees::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'salary' => 'required|numeric',
            'gender' => 'required|string',
            'hired_date' => 'required|date',
            'job_title' => 'required|string',
            'manager_id' => 'nullable|exists:employees,id',
        ]);

        $employee = Employees::create($request->all());
        Log::channel('employee_log')->info("$employee : is created successfully");
        return response()->json(['message' => 'Employee created successfully', 'data' => $employee], 201);
    }

    public function update(Request $request, Employees $employee)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'salary' => 'required|numeric',
            'gender' => 'required|string',
            'hired_date' => 'required|date',
            'job_title' => 'required|string',
            'manager_id' => 'nullable|exists:employees,id',
        ]);

        $employee->update($request->all());
        Log::channel('employee_log')->info("$employee : is updated successfully");
        return response()->json(['message' => 'Employee updated successfully', 'data' => $employee], 200);
    }

    public function destroy(Employees $employee)
    {
        $employee->delete();
        if (!$employee){
            return response()->json(["unauthorized"], 404);
        }
        Log::channel('employee_log')->info("$employee : is deleted successfully");
        return response()->json(['message' => 'Employee deleted successfully']);

    }



    public function getEmployee($id)
    {
        $employee = Employees::find($id);

        if (!$employee) {
            Log::channel('employee_log')->info("Employee with ID $id not found.");
            return response()->json(["error" => "Employee not found"], 404);
        }

        Log::channel('employee_log')->info("Fetched employee with ID: $id");
        return response()->json($employee);
    }


public function getManagers($id)
{
    $employee = Employees::find($id);
    if (!$employee) {
        Log::channel('employee_log')->info("Employee with ID $id not found.");
        return response()->json(["error" => "Employee not found"], 404);
    }

    $managersChain = [];

    while ($employee->manager_id !== null) {
        $manager = Employees::find($employee->manager_id);

        if (!$manager) {
            Log::channel('employee_log')->info("Manager with ID $id not found.");
            return response()->json(["error" => "Manager not found"], 404);
        }

        $managersChain[] = $manager->name;

        $employee = $manager;
    }

    $managersChain = array_reverse($managersChain);

    Log::channel('employee_log')->info("Fetched employee & managers with ID: $id");
    return implode(' - ', $managersChain);

}


    private function getManagersHierarchy($employee)
    {
        $managers = [];
        while ($employee->manager) {
            $managers[] = $employee->manager->name;
            $employee = $employee->manager;
        }
        return $managers;
    }

    public function getManagersWithSalary($id)
    {
        $employee = Employees::find($id);
        if (!$employee) {
            Log::channel('employee_log')->info("Manager with ID $id not found.");
            return response()->json(["error" => "Manager not found"], 404);
        }
        $managers = $this->getManagersHierarchy($employee);
        $managerSalaries = Employees::whereIn('name', $managers)
            ->pluck('name', 'salary')
            ->toArray();

        // Reverse the array before restructuring it
        $managerSalariesReversed = array_reverse($managerSalaries, true);

        // Restructure the array to have the name first, then the salary with the $ sign
        $restructuredData = [];
        foreach ($managerSalariesReversed as $salary => $name) {
            $restructuredData[$name] = '$' . number_format($salary, 2);
        }

        Log::channel('employee_log')->info("Fetched Managers Salaries with ID: $id");
        return response()->json($restructuredData);
    }





    public function searchEmployees(Request $request)
    {
        $searchTerm = $request->query('q', '');

        $employees = Employees::where('name', 'LIKE', '%' . $searchTerm . '%')->get();

        Log::channel('employee_log')->info("Found the employee with ID: $employees");
        return response()->json($employees);
    }

    public function exportToCSV()
    {
        $employees = Employees::select('name', 'age', 'salary', 'gender', 'hired_date', 'job_title', 'manager_id')
            ->get();

        $csvFileName = 'employees_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $callback = function () use ($employees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Age', 'Salary', 'Gender', 'Hired Date', 'Job Title', 'Manager_id']);

            foreach ($employees as $employee) {
                fputcsv($file, $employee->toArray());
            }
            fclose($file);
        };
        Log::channel('employee_log')->info("Data has been exported to CSV file");
        return response()->stream($callback, 200, $headers);
    }

    public function importEmployees(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');

        $job = new ImportEmployeesJob($file);
        dispatch($job);

        Log::channel('employee_log')->info("Data has been imported from CSV file");
        return response()->json(['message' => 'CSV import job has been queued successfully']);
    }

}

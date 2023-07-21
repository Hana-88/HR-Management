<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function getLogsByDate(Request $request, $date)
    {
        $parsedDate = \Carbon\Carbon::parse($date);

        $logs = Logs::whereDate('created_at', $parsedDate)->get();

        if ($logs->isEmpty()) {

            Log::channel('employee_log')->info("Not found this date : $logs");
            return response()->json(['message' => 'No logs found for the specified date.'], 404);
        }
        Log::channel('employee_log')->info("Found this date : $logs");
        return response()->json($logs);
    }
}

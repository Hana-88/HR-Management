<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportDatabase extends Command
{
    protected $signature = 'db:export {filename? : The name of the output file (optional)}';
    protected $description = 'Export the database to a SQL file';

    public function handle()
    {
        // Logic to export the database
        $filename = $this->argument('filename') ?: 'database_export_' . date('Ymd_His') . '.sql';

        $tables = DB::select('SHOW TABLES');
        $tableNames = array_map('current', $tables);

        $content = '';

        foreach ($tableNames as $table) {
            $content .= $this->getTableDataAsSql($table);
        }

        file_put_contents(storage_path('app/' . $filename), $content);

        $this->info("Database exported to: " . storage_path('app/' . $filename));
    }

    private function getTableDataAsSql($tableName)
    {
        $data = DB::table($tableName)->get()->toArray();
        $columns = DB::getSchemaBuilder()->getColumnListing($tableName);

        $sql = "INSERT INTO `$tableName` (`" . implode('`, `', $columns) . "`) VALUES ";

        foreach ($data as $row) {
            $values = array_map(function ($value) {
                return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
            }, (array)$row);

            $sql .= "(" . implode(', ', $values) . "), ";
        }

        $sql = rtrim($sql, ", ") . ";\n\n";

        return $sql;
    }
}

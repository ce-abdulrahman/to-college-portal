<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('departments.sql');

        if (!is_file($path)) {
            $this->command?->error('departments.sql file not found at project root.');
            return;
        }

        $sql = file_get_contents($path);

        if ($sql === false || trim($sql) === '') {
            $this->command?->error('departments.sql is empty or unreadable.');
            return;
        }

        $statements = $this->splitSqlStatements($sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }
            DB::statement($statement);
        }

        $this->command?->info('✅ Departments seeded successfully from departments.sql');
    }

    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $inSingle = false;
        $inDouble = false;
        $escaped = false;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            if ($escaped) {
                $buffer .= $char;
                $escaped = false;
                continue;
            }

            if ($char === '\\') {
                $buffer .= $char;
                $escaped = true;
                continue;
            }

            if ($char === "'" && !$inDouble) {
                $inSingle = !$inSingle;
                $buffer .= $char;
                continue;
            }

            if ($char === '"' && !$inSingle) {
                $inDouble = !$inDouble;
                $buffer .= $char;
                continue;
            }

            if ($char === ';' && !$inSingle && !$inDouble) {
                $statements[] = $buffer;
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        if (trim($buffer) !== '') {
            $statements[] = $buffer;
        }

        return $statements;
    }
}

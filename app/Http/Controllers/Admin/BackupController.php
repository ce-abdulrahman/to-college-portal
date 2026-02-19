<?php
// [file name]: BackupController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use PDO;
use Exception;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::orderByDesc('created_at')->paginate(15);
        return view('website.web.admin.backups.index', compact('backups'));
    }

    public function create()
    {
        return view('website.web.admin.backups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_db' => 'required|in:sqlite,mysql',
            'notes' => 'nullable|string'
        ]);

        // دروستکردنی ناوی فایل
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fileName = "backup_{$timestamp}_{$request->target_db}";
        
        if ($request->target_db === 'sqlite') {
            $fileName .= '.sqlite';
        } else {
            $fileName .= '.sql';
        }
        
        // دروستکردنی Backup - file_path تەنها ناوی فایلە
        $backup = Backup::create([
            'name' => $request->name . '_' . $timestamp,
            'file_path' => $fileName, // تەنها ناوی فایل!
            'database_type' => DB::connection()->getDriverName(),
            'source_db' => DB::connection()->getDatabaseName(),
            'target_db' => $request->target_db,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        // دروستکردنی Backup لە پاشەکەوتەوە
        $this->createBackup($backup);

        return redirect()->route('admin.backups.index')
            ->with('success', 'Backup دروستکرا بە سەرکەوتوویی!');
    }

private function createBackup(Backup $backup)
{
    try {
        $this->ensureBackupDirectory();

        if ($backup->target_db === 'sqlite') {
            $this->createSQLiteBackup($backup);
        } else {
            $this->createMySQLBackup($backup);
        }
    } catch (\Exception $e) {
        \Log::error("Backup هەڵە: " . $e->getMessage());

        $backup->update([
            'status' => 'failed',
            'notes' => 'هەڵە: ' . $e->getMessage(),
        ]);

        throw $e;
    }
}
private function createMySQLBackupFile(string $filePath)
{
    $tables = DB::select('SHOW TABLES');
    $dbName = config('database.connections.mysql.database');
    $tableNames = array_column($tables, 'Tables_in_' . $dbName);
    
    $sqlContent = "";
    $totalRecords = 0;
    
    foreach ($tableNames as $table) {
        // CREATE TABLE
        $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
        if (empty($createResult)) {
            continue;
        }
        
        $createSQL = $createResult[0]->{'Create Table'};
        $sqlContent .= "-- Table: {$table}\n";
        $sqlContent .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sqlContent .= $createSQL . ";\n\n";
        
        // INSERT داتا
        $rows = DB::table($table)->get();
        
        if ($rows->count() > 0) {
            $columns = Schema::getColumnListing($table);
            
            foreach ($rows as $row) {
                $values = [];
                
                foreach ($columns as $column) {
                    $value = $row->$column ?? null;
                    
                    if ($value === null) {
                        $values[] = 'NULL';
                    } elseif (is_numeric($value)) {
                        $values[] = $value;
                    } elseif (is_bool($value)) {
                        $values[] = $value ? 1 : 0;
                    } else {
                        $escapedValue = str_replace("'", "''", $value);
                        $values[] = "'{$escapedValue}'";
                    }
                }
                
                $sqlContent .= "INSERT INTO `{$table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                $totalRecords++;
            }
            
            $sqlContent .= "\n";
        }
    }
    
    file_put_contents($filePath, $sqlContent);
    
    return ['tables' => count($tableNames), 'records' => $totalRecords];
}
private function createMySQLBackup(Backup $backup)
{
    // دروستکردنی ناو و ڕێگە
    $timestamp = now()->format('Y-m-d_H-i-s');
    $fileName = "backup_mysql_{$timestamp}.sql";
    $storagePath = storage_path("app/backups/{$fileName}");
    $publicPath = public_path("storage/backups/{$fileName}");
    
    // دڵنیابوونەوە لە دایرەکتۆری
    $this->ensureBackupDirectory();
    
    // نوێکردنەوەی Backup
    $backup->update([
        'file_path' => $fileName,
        'status' => 'processing'
    ]);
    
    
    // Detect Driver
    $driver = DB::connection()->getDriverName();
    $tableNames = [];

    if ($driver === 'sqlite') {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $tableNames = array_column($tables, 'name');
    } else {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tableNames = array_column($tables, 'Tables_in_' . $dbName);
    }
    
    $sqlContent = "";
    $totalRecords = 0;
    
    // دروستکردنی SQL
    foreach ($tableNames as $table) {
        // CREATE TABLE
        $createSQL = "";
        
        if ($driver === 'sqlite') {
             $result = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name = ?", [$table]);
             $createSQL = $result[0]->sql ?? '';
        } else {
             $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
             if (empty($createResult)) { continue; }
             $createSQL = $createResult[0]->{'Create Table'};
        }
        
        $sqlContent .= "-- Table: {$table}\n";
        $sqlContent .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sqlContent .= $createSQL . ";\n\n";
        
        // INSERT داتا
        $rows = DB::table($table)->get();
        $rowCount = $rows->count();
        
        if ($rowCount > 0) {
            $columns = Schema::getColumnListing($table);
            
            foreach ($rows as $row) {
                $values = [];
                
                foreach ($columns as $column) {
                    $value = $row->$column ?? null;
                    
                    if ($value === null) {
                        $values[] = 'NULL';
                    } elseif (is_numeric($value)) {
                        $values[] = $value;
                    } elseif (is_bool($value)) {
                        $values[] = $value ? 1 : 0;
                    } else {
                        $escapedValue = str_replace("'", "''", $value);
                        $values[] = "'{$escapedValue}'";
                    }
                }
                
                $sqlContent .= "INSERT INTO `{$table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                $totalRecords++;
            }
            
            $sqlContent .= "\n";
        }
    }
    
    // پاشەکەوتکردنی فایل
    file_put_contents($storagePath, $sqlContent);
    
    // کۆپیکردن بۆ public
    if (file_exists($storagePath)) {
        copy($storagePath, $publicPath);
    }
    
    // نوێکردنەوەی Backup
    $backup->update([
        'tables_count' => count($tableNames),
        'records_count' => $totalRecords,
        'file_size' => round(filesize($storagePath) / 1024, 2),
        'status' => 'completed',
        'updated_at' => now()
    ]);
    
    \Log::info("MySQL Backup دروستکرا: {$fileName}");
}
private function createSQLiteBackup(Backup $backup)
{
    // دروستکردنی ناو و ڕێگە
    $timestamp = now()->format('Y-m-d_H-i-s');
    $fileName = "backup_sqlite_{$timestamp}.sqlite";
    $storagePath = storage_path("app/backups/{$fileName}");
    $publicPath = public_path("storage/backups/{$fileName}");
    
    // دڵنیابوونەوە لە دایرەکتۆری
    $this->ensureBackupDirectory();
    
    // نوێکردنەوەی Backup
    $backup->update([
        'file_path' => $fileName,
        'status' => 'processing'
    ]);
    
    // 1. Check source driver
    $driver = DB::connection()->getDriverName();
    
    // If source is already SQLite, just copy the file!
    if ($driver === 'sqlite') {
        $sourcePath = config('database.connections.sqlite.database');
        // If config is null or relative, fallback
        if (!$sourcePath || !file_exists($sourcePath)) {
            $sourcePath = database_path('database.sqlite');
        }
        
        if (file_exists($sourcePath)) {
            copy($sourcePath, $storagePath);
            
             // Status update
            $backup->update([
                'file_size' => round(filesize($storagePath) / 1024, 2),
                'status' => 'completed',
                'updated_at' => now(),
                'tables_count' => DB::table('sqlite_master')->where('type', 'table')->where('name', 'not like', 'sqlite_%')->count(),
                'records_count' => 0 // Calculating total records is expensive, skip or do loop if needed
            ]);
            
            \Log::info("SQLite Backup copied successfully: {$fileName}");
            // Copy to public
            if (file_exists($storagePath)) {
                copy($storagePath, $publicPath);
            }
            return;
        }
    }

    // Existing fallback for MySQL source -> SQLite target
    $tables = DB::select('SHOW TABLES');
    $dbName = config('database.connections.mysql.database');
    $tableNames = array_column($tables, 'Tables_in_' . $dbName);
    
    // 2. دووەم: دروستکردنی داتابەیسێکی SQLite نوێ
    $sqliteDb = new \PDO("sqlite:{$storagePath}");
    $sqliteDb->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    $totalRecords = 0;
    
    // 3. سێیەم: گواستنەوەی هەر تابلێک
    foreach ($tableNames as $table) {
        try {
            // 3.1. وەرگرتنی CREATE TABLE لە MySQL
            $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
            if (empty($createResult)) {
                continue;
            }
            
            $mysqlCreateSQL = $createResult[0]->{'Create Table'};
            
            // 3.2. گۆڕینی MySQL syntax بۆ SQLite
            $sqliteCreateSQL = $this->convertMySQLToSQLite($mysqlCreateSQL);
            
            // 3.3. دروستکردنی تابل لە SQLite
            $sqliteDb->exec($sqliteCreateSQL);
            
            // 3.4. وەرگرتنی داتا لە MySQL
            $rows = DB::table($table)->get();
            $rowCount = $rows->count();
            
            if ($rowCount > 0) {
                $columns = Schema::getColumnListing($table);
                
                // بۆ هەر ڕیزێک
                foreach ($rows as $row) {
                    // دروستکردنی INSERT statement بۆ SQLite
                    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
                    $columnNames = implode(', ', array_map(function($col) {
                        return '"' . $col . '"';
                    }, $columns));
                    
                    $sql = "INSERT INTO \"{$table}\" ({$columnNames}) VALUES ({$placeholders})";
                    $stmt = $sqliteDb->prepare($sql);
                    
                    // Bind کردنەوەی نرخەکان
                    $index = 1;
                    foreach ($columns as $column) {
                        $value = $row->$column ?? null;
                        
                        if ($value === null) {
                            $stmt->bindValue($index, null, \PDO::PARAM_NULL);
                        } elseif (is_numeric($value)) {
                            $stmt->bindValue($index, $value, is_float($value) ? \PDO::PARAM_STR : \PDO::PARAM_INT);
                        } elseif (is_bool($value)) {
                            $stmt->bindValue($index, $value ? 1 : 0, \PDO::PARAM_INT);
                        } else {
                            $stmt->bindValue($index, (string)$value, \PDO::PARAM_STR);
                        }
                        
                        $index++;
                    }
                    
                    $stmt->execute();
                    $totalRecords++;
                }
            }
            
        } catch (\Exception $e) {
            \Log::warning("هەڵە لە گواستنەوەی تابیلی {$table}: " . $e->getMessage());
            continue;
        }
    }
    
    // داخستنی پەیوەندی SQLite
    $sqliteDb = null;
    
    // کۆپیکردن بۆ public
    if (file_exists($storagePath)) {
        copy($storagePath, $publicPath);
    }
    
    // نوێکردنەوەی Backup
    $backup->update([
        'tables_count' => count($tableNames),
        'records_count' => $totalRecords,
        'file_size' => round(filesize($storagePath) / 1024, 2),
        'status' => 'completed',
        'updated_at' => now()
    ]);
    
    \Log::info("SQLite Backup دروستکرا: {$fileName}");
}


private function convertMySQLToSQLite(string $mysqlSQL): string
{
    $sqliteSQL = str_replace(["\r\n", "\r"], "\n", $mysqlSQL);
    $sqliteSQL = str_replace('`', '"', $sqliteSQL);

    // remove MySQL table options and clauses that SQLite does not support
    $sqliteSQL = preg_replace('/\)\s*ENGINE=.*$/is', ')', $sqliteSQL);
    $sqliteSQL = preg_replace('/\)\s*DEFAULT CHARSET=.*$/is', ')', $sqliteSQL);
    $sqliteSQL = preg_replace('/\)\s*CHARSET=.*$/is', ')', $sqliteSQL);
    $sqliteSQL = preg_replace('/\s+COLLATE\s*=\s*[\w\d_]+/i', '', $sqliteSQL);
    $sqliteSQL = preg_replace('/\s+AUTO_INCREMENT=\d+/i', '', $sqliteSQL);

    // remove secondary keys/constraints to avoid MySQL-specific syntax errors
    $sqliteSQL = preg_replace('/,\s*(UNIQUE\s+)?KEY\s+"[^"]+"\s*\([^)]+\)/i', '', $sqliteSQL);
    $sqliteSQL = preg_replace('/,\s*CONSTRAINT\s+"[^"]+"\s+FOREIGN KEY\s*\([^)]+\)\s+REFERENCES\s+"[^"]+"\s*\([^)]+\)(?:\s+ON DELETE [A-Z ]+)?(?:\s+ON UPDATE [A-Z ]+)?/i', '', $sqliteSQL);

    $patterns = [
        '/\bBIGINT\(\d+\)\b/i' => 'INTEGER',
        '/\bINT\(\d+\)\b/i' => 'INTEGER',
        '/\bMEDIUMINT\(\d+\)\b/i' => 'INTEGER',
        '/\bSMALLINT\(\d+\)\b/i' => 'INTEGER',
        '/\bTINYINT\(\d+\)\b/i' => 'INTEGER',
        '/\bDECIMAL\([^)]+\)\b/i' => 'NUMERIC',
        '/\bDOUBLE\([^)]+\)\b/i' => 'REAL',
        '/\bFLOAT\([^)]+\)\b/i' => 'REAL',
        '/\bVARCHAR\(\d+\)\b/i' => 'TEXT',
        '/\bCHAR\(\d+\)\b/i' => 'TEXT',
        '/\bLONGTEXT\b/i' => 'TEXT',
        '/\bMEDIUMTEXT\b/i' => 'TEXT',
        '/\bTEXT\b/i' => 'TEXT',
        '/\bDATETIME\b/i' => 'TEXT',
        '/\bTIMESTAMP\b/i' => 'TEXT',
        '/\bUNSIGNED\b/i' => '',
        '/\bCOMMENT\s+\'[^\']*\'/i' => '',
        '/\s+ON UPDATE CURRENT_TIMESTAMP(?:\(\))?/i' => '',
        '/\bAUTO_INCREMENT\b/i' => 'AUTOINCREMENT',
    ];

    foreach ($patterns as $pattern => $replacement) {
        $sqliteSQL = preg_replace($pattern, $replacement, $sqliteSQL);
    }

    // SQLite requires: INTEGER PRIMARY KEY AUTOINCREMENT (single column)
    if (preg_match('/"([^"]+)"\s+INTEGER[^,]*AUTOINCREMENT/i', $sqliteSQL, $matches)) {
        $columnName = $matches[1];
        $escapedColumn = preg_quote($columnName, '/');

        $sqliteSQL = preg_replace(
            '/"' . $escapedColumn . '"\s+INTEGER[^,]*AUTOINCREMENT/i',
            '"' . $columnName . '" INTEGER PRIMARY KEY AUTOINCREMENT',
            $sqliteSQL,
            1
        );

        $sqliteSQL = preg_replace('/,\s*PRIMARY KEY\s*\(\s*"' . $escapedColumn . '"\s*\)/i', '', $sqliteSQL, 1);
    }

    // clean up final SQL formatting
    $sqliteSQL = preg_replace('/,\s*\)/', ')', $sqliteSQL);
    $sqliteSQL = preg_replace('/\s+/', ' ', trim($sqliteSQL));

    return rtrim($sqliteSQL, ';') . ';';
}
private function createSQLiteDatabase(string $filePath)
{
    // دروستکردنی داتابەیسێکی SQLite نوێ
    $pdo = new \PDO("sqlite:{$filePath}");
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    // وەرگرتنی تابلەکان لە MySQL
    $tables = DB::select('SHOW TABLES');
    $dbName = config('database.connections.mysql.database');
    $tableNames = array_column($tables, 'Tables_in_' . $dbName);
    
    $totalRecords = 0;
    
    // گواستنەوەی هەر تابلێک
    foreach ($tableNames as $table) {
        try {
            // وەرگرتنی CREATE TABLE لە MySQL
            $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
            if (empty($createResult)) {
                continue;
            }
            
            $mysqlCreateSQL = $createResult[0]->{'Create Table'};
            
            // گۆڕینی MySQL بۆ SQLite syntax
            $sqliteCreateSQL = $this->convertMySQLToSQLite($mysqlCreateSQL);
            
            // دروستکردنی تابل لە SQLite
            $pdo->exec($sqliteCreateSQL);
            
            // وەرگرتنی داتا لە MySQL
            $rows = DB::table($table)->get();
            
            if ($rows->count() > 0) {
                $columns = Schema::getColumnListing($table);
                
                foreach ($rows as $row) {
                    // دروستکردنی INSERT statement
                    $columnNames = implode(', ', array_map(function($col) {
                        return '"' . $col . '"';
                    }, $columns));
                    
                    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
                    $sql = "INSERT INTO \"{$table}\" ({$columnNames}) VALUES ({$placeholders})";
                    
                    $stmt = $pdo->prepare($sql);
                    
                    // Bind کردنەوەی نرخەکان
                    $index = 1;
                    foreach ($columns as $column) {
                        $value = $row->$column ?? null;
                        
                        if ($value === null) {
                            $stmt->bindValue($index, null, \PDO::PARAM_NULL);
                        } elseif (is_numeric($value)) {
                            $stmt->bindValue($index, $value, is_float($value) ? \PDO::PARAM_STR : \PDO::PARAM_INT);
                        } elseif (is_bool($value)) {
                            $stmt->bindValue($index, $value ? 1 : 0, \PDO::PARAM_INT);
                        } else {
                            $stmt->bindValue($index, (string)$value, \PDO::PARAM_STR);
                        }
                        
                        $index++;
                    }
                    
                    $stmt->execute();
                    $totalRecords++;
                }
            }
            
        } catch (\Exception $e) {
            \Log::warning("هەڵە لە تابیلی {$table}: " . $e->getMessage());
            continue;
        }
    }
    
    return $totalRecords;
}

    private function ensureBackupDirectory()
    {
        // دروستکردنی دایرەکتۆری لە storage/app/backups
        $storagePath = storage_path('app/backups');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        
        // دروستکردنی دایرەکتۆری لە public/storage/backups (بۆ لینکی سمبۆلیک)
        $publicPath = public_path('storage/backups');
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
    }

    private function convertToSQLite(Backup $backup, $mysqlFilePath)
    {
        $sqliteFileName = str_replace('.sql', '.sqlite', basename($mysqlFilePath));
        $sqlitePath = "backups/{$sqliteFileName}";
        
        // دروستکردنی فایلێکی SQLite نوێ
        $sqliteDb = new PDO('sqlite:' . storage_path("app/{$sqlitePath}"));
        $sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // خوێندنەوەی فایلەکەی MySQL
        $mysqlContent = Storage::disk('local')->get($mysqlFilePath);
        $statements = explode(';', $mysqlContent);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !str_starts_with($statement, '--')) {
                // گۆڕینی MySQL syntax بۆ SQLite
                $statement = $this->convertMySQLToSQLite($statement);
                
                try {
                    $sqliteDb->exec($statement);
                } catch (Exception $e) {
                    // پەڕەوە ببە لە هەڵەکانی syntax
                    continue;
                }
            }
        }
        
        // نوێکردنەوەی Backup بۆ SQLite
        $backup->update([
            'file_path' => $sqlitePath,
            'file_size' => Storage::disk('local')->size($sqlitePath) / 1024
        ]);
    }

    private function resolveBackupStoragePath(Backup $backup): string
    {
        $fileName = basename((string) $backup->file_path);
        return storage_path("app/backups/{$fileName}");
    }

    private function resolveSQLiteDatabasePath(): string
    {
        $configuredPath = config('database.connections.sqlite.database');

        if (!$configuredPath || $configuredPath === ':memory:') {
            return database_path('database.sqlite');
        }

        // absolute unix path or absolute windows path
        if (str_starts_with($configuredPath, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:\\\\/', $configuredPath)) {
            return $configuredPath;
        }

        return base_path($configuredPath);
    }

    private function splitSqlStatements(string $sql): array
    {
        // remove single-line comments generated by backup builder
        $sql = preg_replace('/^\s*--.*$/m', '', $sql);

        $statements = [];
        $buffer = '';
        $inSingleQuote = false;
        $inDoubleQuote = false;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $prev = $i > 0 ? $sql[$i - 1] : '';

            if ($char === "'" && !$inDoubleQuote && $prev !== '\\') {
                $inSingleQuote = !$inSingleQuote;
            } elseif ($char === '"' && !$inSingleQuote && $prev !== '\\') {
                $inDoubleQuote = !$inDoubleQuote;
            }

            if ($char === ';' && !$inSingleQuote && !$inDoubleQuote) {
                $statement = trim($buffer);
                if ($statement !== '') {
                    $statements[] = $statement;
                }
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        $statement = trim($buffer);
        if ($statement !== '') {
            $statements[] = $statement;
        }

        return $statements;
    }

    

    public function restore($id)
    {
        $backup = Backup::findOrFail($id);
        
        return view('website.web.admin.backups.restore', compact('backup'));
    }

    public function performRestore(Request $request, $id)
    {
        $request->validate([
            'target_database' => 'required|in:mysql,sqlite',
            'confirm' => 'required|accepted'
        ]);
        
        $backup = Backup::findOrFail($id);
        
        try {
            if ($backup->target_db === 'sqlite' && $request->target_database === 'mysql') {
                $this->restoreSQLiteToMySQL($backup);
            } elseif ($backup->target_db === 'mysql' && $request->target_database === 'sqlite') {
                $this->restoreMySQLToSQLite($backup);
            } else {
                // هەمان جۆری داتابەیس
                $this->restoreSameType($backup, $request->target_database);
            }
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Restore بە سەرکەوتوویی ئەنجامدرا!');
                
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'هەڵە لە Restore: ' . $e->getMessage());
        }
    }

    private function restoreSQLiteToMySQL(Backup $backup)
    {
        $sqlitePath = $this->resolveBackupStoragePath($backup);
        
        if (!file_exists($sqlitePath)) {
            throw new Exception("فایلەکەی SQLite نەدۆزرایەوە");
        }
        
        // پەیوەندی SQLite
        $sqliteDb = new PDO('sqlite:' . $sqlitePath);
        $sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // وەرگرتنی هەموو تابلەکان
        $tablesQuery = $sqliteDb->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($tables as $table) {
                // دروستکردنی تابل لە MySQL
                $createTable = $sqliteDb->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$table}'")->fetchColumn();
                if (!$createTable) {
                    continue;
                }

                // گۆڕینی SQLite بۆ MySQL
                $createTable = $this->convertSQLiteToMySQL($createTable);

                // دروستکردنی تابل
                DB::statement("DROP TABLE IF EXISTS `{$table}`");
                DB::statement($createTable);

                // وەرگرتنی داتا
                $rows = $sqliteDb->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                        DB::table($table)->insert($row);
                    }
                }
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function restoreMySQLToSQLite(Backup $backup)
    {
        $sqlPath = $this->resolveBackupStoragePath($backup);

        if (!file_exists($sqlPath)) {
            throw new Exception("فایلەکەی MySQL نەدۆزرایەوە");
        }

        $sqlitePath = $this->resolveSQLiteDatabasePath();
        $sqliteDirectory = dirname($sqlitePath);

        if (!is_dir($sqliteDirectory)) {
            mkdir($sqliteDirectory, 0755, true);
        }

        DB::purge('sqlite');

        if (file_exists($sqlitePath)) {
            unlink($sqlitePath);
        }

        $sqliteDb = new PDO('sqlite:' . $sqlitePath);
        $sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlContent = file_get_contents($sqlPath);
        if ($sqlContent === false) {
            throw new Exception("ناتوانرێت فایلەکەی backup بخوێندرێتەوە");
        }

        $statements = $this->splitSqlStatements($sqlContent);

        foreach ($statements as $statement) {
            $statement = trim($statement);

            if ($statement === '' || str_starts_with($statement, '--')) {
                continue;
            }

            if (stripos($statement, 'CREATE TABLE') === 0) {
                $statement = $this->convertMySQLToSQLite($statement);
            } else {
                $statement = str_replace('`', '"', $statement);
            }

            $sqliteDb->exec($statement);
        }

        $sqliteDb = null;
        DB::reconnect('sqlite');
    }

    private function restoreSameType(Backup $backup, string $targetDatabase)
    {
        if ($targetDatabase === 'sqlite') {
            $sqliteBackupPath = $this->resolveBackupStoragePath($backup);
            if (!file_exists($sqliteBackupPath)) {
                throw new Exception("فایلەکەی SQLite نەدۆزرایەوە");
            }

            $sqliteTargetPath = $this->resolveSQLiteDatabasePath();
            $sqliteDirectory = dirname($sqliteTargetPath);

            if (!is_dir($sqliteDirectory)) {
                mkdir($sqliteDirectory, 0755, true);
            }

            DB::purge('sqlite');
            if (!copy($sqliteBackupPath, $sqliteTargetPath)) {
                throw new Exception("کۆپی کردنی backup بۆ SQLite سەرکەوتوو نەبوو");
            }
            DB::reconnect('sqlite');
            return;
        }

        $sqlPath = $this->resolveBackupStoragePath($backup);
        if (!file_exists($sqlPath)) {
            throw new Exception("فایلەکەی MySQL نەدۆزرایەوە");
        }

        $sqlContent = file_get_contents($sqlPath);
        if ($sqlContent === false) {
            throw new Exception("ناتوانرێت فایلەکەی backup بخوێندرێتەوە");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($this->splitSqlStatements($sqlContent) as $statement) {
                if (str_starts_with(trim($statement), '--')) {
                    continue;
                }

                DB::unprepared($statement);
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function convertSQLiteToMySQL(string $sql): string
    {
        $replacements = [
            '/INTEGER PRIMARY KEY AUTOINCREMENT/i' => 'INT PRIMARY KEY AUTO_INCREMENT',
            '/AUTOINCREMENT/i' => 'AUTO_INCREMENT',
            '/"([^"]+)"/i' => '`$1`', // گۆڕینی quotes
            '/TEXT/i' => 'LONGTEXT',
        ];
        
        foreach ($replacements as $pattern => $replacement) {
            $sql = preg_replace($pattern, $replacement, $sql);
        }
        
        return rtrim($sql, ';') . ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
    }

    public function download($id)
    {
        $backup = Backup::findOrFail($id);
        
        $fileName = basename((string) $backup->file_path);
        $storagePath = $this->resolveBackupStoragePath($backup);
        
        if (!file_exists($storagePath)) {
            abort(404, 'فایلەکە نەدۆزرایەوە');
        }
        
        return response()->download($storagePath, $fileName);
    }

public function destroy($id)
{
    $backup = Backup::findOrFail($id);
    $fileName = basename((string) $backup->file_path);
    
    // سڕینەوەی فایل لە storage/app/backups/
    $storagePath = storage_path("app/backups/{$fileName}");
    if (file_exists($storagePath)) {
        unlink($storagePath);
    }
    
    // سڕینەوەی فایل لە public/storage/backups/ (ئەگەر بوونی هەبوو)
    $publicPath = public_path("storage/backups/{$fileName}");
    if (file_exists($publicPath)) {
        unlink($publicPath);
    }
    
    // سڕینەوەی تۆمار
    $backup->delete();
    
    return redirect()->route('admin.backups.index')
        ->with('success', 'Backup سڕدرایەوە!');
}
}

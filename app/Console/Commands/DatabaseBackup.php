<?php
// [file name]: DatabaseBackup.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Backup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup {type=sqlite : sqlite یان mysql} {--name= : ناوی Backup}';
    protected $description = 'Backup داتابەیس بۆ SQLite یان MySQL';

    public function handle()
    {
        $type = $this->argument('type');
        $name = $this->option('name') ?? 'Backup_' . now()->format('Y-m-d_H-i-s');
        
        $this->info("دەستپێکردنی Backup بۆ {$type}...");
        
        $backup = Backup::create([
            'name' => $name,
            'database_type' => 'mysql',
            'target_db' => $type,
            'status' => 'pending'
        ]);
        
        // وەرگرتنی تابلەکان
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tableNames = array_column($tables, 'Tables_in_' . $dbName);
        
        $fileName = "backup_" . now()->format('Y-m-d_H-i-s') . ".{$type}";
        $filePath = "backups/{$fileName}";
        
        if ($type === 'sqlite') {
            $this->backupToSQLite($backup, $tableNames, $filePath);
        } else {
            $this->backupToMySQL($backup, $tableNames, $filePath);
        }
        
        $this->info("Backup تەواو بوو! فایل: " . $filePath);
    }
    
    private function backupToSQLite($backup, $tables, $filePath)
    {
        // کۆدی Backup بۆ SQLite
        // (کۆدی پێشوو بەکاربهێنە)
    }
    
    private function backupToMySQL($backup, $tables, $filePath)
    {
        // کۆدی Backup بۆ MySQL
        // (کۆدی پێشوو بەکاربهێنە)
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'db-backup';
    protected $description = 'Backup the database';

    public function handle()
    {
        $filename = 'backup' . now()->format('Y-m-d-H-i-s') . '.sql';
        $command = 'mysqldump --user=' . env('DB_USERNAME') . ' --password=' . env('DB_PASSWORD')
         . ' --host=' . env('DB_HOST') . ' --port=' . env('DB_PORT') . ' ' . env('DB_DATABASE') . ' > ' . public_path() . '/backup/' . $filename;
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        Log::info('Database backup completed.');
    }
}

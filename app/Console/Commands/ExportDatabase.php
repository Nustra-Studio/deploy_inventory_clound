<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    protected $signature = 'database:export';
    protected $description = 'Export database to SQL file';
    public function handle()
    {
        $backupFileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $backupFileName);

        $command = sprintf(
            'mysqldump -u%s -p%s -h %s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $backupPath
        );

        // Eksekusi perintah mysqldump
        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            $this->info('Database exported successfully.');
            $this->info('Backup file saved at: ' . $backupPath);
        } else {
            $this->error('Error exporting database.');
        }
    }
}

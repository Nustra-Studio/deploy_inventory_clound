<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
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

        $process = new Process([
            'mysqldump',
            '-u' . config('database.connections.mysql.username'),
            '-p' . config('database.connections.mysql.password'),
            '-h' . config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
        ]);
        
        try {
            $process->mustRun();
            $output = $process->getOutput();
            $this->info('Database exported successfully.');
            // Simpan $output ke file jika perlu
        } catch (ProcessFailedException $exception) {
            $this->error('Error exporting database.');
        }

        if ($resultCode === 0) {
            $this->info('Database exported successfully.');
            $this->info('Backup file saved at: ' . $backupPath);
        } else {
            $this->error('Error exporting database.');
        }
    }
}

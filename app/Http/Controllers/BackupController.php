<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    /**
     * RUN BACKUP
     */
    public function run()
    {
        try {
            Artisan::call('backup:run');
            $output = Artisan::output();

            Log::info('Backup completed', [
                'output' => $output
            ]);

            return redirect()->back()->with('success', 'Backup completed successfully.');
        } catch (\Throwable $e) {

            Log::error('Backup failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Backup failed.');
        }
    }

    /**
     * DOWNLOAD LATEST BACKUP
     */
    public function download()
    {
        try {
            $disk = Storage::disk('local');

            $files = collect($disk->allFiles())
                ->filter(fn ($file) =>
                    str_contains($file, 'backup') &&
                    str_ends_with($file, '.zip')
                )
                ->sortByDesc(fn ($file) => $disk->lastModified($file))
                ->values();

            if ($files->isEmpty()) {
                return redirect()->back()->with('error', 'No backup found.');
            }

            return $disk->download($files->first());

        } catch (\Throwable $e) {

            Log::error('Backup download failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Download failed.');
        }
    }
}
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Artisan Commands
|--------------------------------------------------------------------------
*/

// Default Laravel inspire command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/*
|--------------------------------------------------------------------------
| Scheduled Tasks (CRON JOBS)
|--------------------------------------------------------------------------
|
| This is where we schedule automatic system tasks like backups,
| emails, reports, etc.
|
*/

// Daily automatic backup at 1:00 AM
Schedule::command('backup:run')
    ->dailyAt('01:00')
    ->description('Daily system backup for school finance system');


// Optional: clean old backups (if using Spatie cleanup)
Schedule::command('backup:clean')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->description('Clean old backups');
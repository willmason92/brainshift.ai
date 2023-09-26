<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //purge upload directory
        $schedule->call(function () {
            $disk = Storage::disk('public');
            $yesturday = now()->subDay();
            $deletable = [];
            foreach ($disk->allFiles('upload') as $file) {
                if (Carbon::createFromTimestamp($disk->lastModified($file))->lte($yesturday)) {
                    $deletable[] = $file;
                }
            }
            if (! empty($deletable)) {
                $disk->delete($deletable);
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

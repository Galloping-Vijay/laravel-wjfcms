<?php

namespace App\Console;

use App\Console\Commands\LogInfo;
use App\Console\Commands\SendLinkSubmit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{
    /**
     * 应用中自定义的 Artisan 命令
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        //LogInfo::class,
        SendLinkSubmit::class,
    ];

    /**
     * 定义计划任务
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //log
        //$schedule->command('lesson:log')->everyMinute();
        $schedule->command('send:linkSubmit')->everyMinute()->when(function () {
            if (Cache::get('link_remain') > 20) {
                return true;
            }
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

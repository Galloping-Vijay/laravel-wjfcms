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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //log
        //$schedule->command('lesson:log')->everyMinute();
        $schedule->command('send:linkSubmit')->everyMinute()->when(function () {
            $link_remain = Cache::get('link_remain');
            if (is_null($link_remain) || $link_remain > 20) {
                return true;
            } else if (date('G') == '0' && $link_remain <= 20) {
                //如果是一天的开始,并且$link_remain<=20,则设置成null,就可以重新推送了
                Cache::put('link_remain', null);
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

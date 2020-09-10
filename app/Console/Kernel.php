<?php

namespace App\Console;
use DB;
use App\Http\Controllers\Tps\TpsScheduleController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
        ->everyMinute();	Run the task every minute
        ->everyFiveMinutes();	Run the task every five minutes
        ->everyTenMinutes();	Run the task every ten minutes
        ->everyThirtyMinutes();	Run the task every thirty minutes
        ->hourly();	Run the task every hour
        ->daily();	Run the task every day at midnight
        ->dailyAt('13:00');	Run the task every day at 13:00
        ->twiceDaily(1, 13);	Run the task daily at 1:00 & 13:00
        ->weekly();	Run the task every week
        ->monthly();	Run the task every month
        ->monthlyOn(4, '15:00');	Run the task every month on the 4th at 15:00
        ->quarterly();	Run the task every quarter
        ->yearly();
        */
        
//        $schedule->command('inspire')
//                 ->timezone('Asia/Jakarta')
//                  ->everyMinute();
        
        // Create XML COARI & CODECO CONTAINER Every 10 Minutes
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->createXmlCoariCont();
        })->everyTenMinutes();
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->createXmlCodecoCont();
        })->everyTenMinutes();
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->createXmlCoariKms();
        })->everyTenMinutes();
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->createXmlCodecoKms();
        })->everyTenMinutes();
        
        // Send TPS Online COARI & CODECO CONTAINER Every 5 Minutes
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->sendXmlCoariCont();
        })->everyMinute();
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->sendXmlCodecoCont();
        })->everyMinute();
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->sendXmlCoariKms();
        })->everyMinute();
        $schedule->call(function () {
            $controller = new TpsScheduleController();
            $controller->sendXmlCodecoKms();
        })->everyMinute();
    }
}

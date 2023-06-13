<?php

namespace App\Console;

use App\Jobs\UpdatePaymentTransactions;
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
        Commands\GenerateJWTKeys::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        // $schedule->call(function() {
        //     UpdatePaymentTransactions::dispatch();
        // })->everyFifteenMinutes();

        //ensure the range of ip address are up to date
        $schedule->command('cloudflare:reload')->daily();
        $schedule->command('synchronize:products')->everyFiveMinutes();
        $schedule->command('synchronize:orders')->everyTenMinutes();
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

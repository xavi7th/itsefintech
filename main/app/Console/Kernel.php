<?php

namespace App\Console;

use Nwidart\Modules\Facades\Module;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    $schedule->command('bleyt:create-wallets')
    ->everyMinute()
    ->sendOutputTo(Module::getModulePath('Admin/Console') . '/1create-bleyt-wallet-log-' . now()->toDateString() . '.cson')
    ->onFailure(function () {
      // ActivityLog::notifyAdmins('Compounding due interests of target savings failed to complete successfully');
    });

    $schedule->command('bleyt:activate-debit-cards')
    ->everyMinute()
    ->sendOutputTo(Module::getModulePath('Admin/Console') . '/1activate-bleyt-wallet-log-' . now()->toDateString() . '.cson')
    ->onFailure(function () {
      // ActivityLog::notifyAdmins('Compounding due interests of target savings failed to complete successfully');
    });

    $schedule->command('queue:restart')->hourly();
    $schedule->command('queue:work --sleep=3 --timeout=900 --queue=high,default,low')->runInBackground()->withoutOVerlapping()->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    $this->load(Module::getModulePath('Admin/Console'));

        require base_path('routes/console.php');
    }
}

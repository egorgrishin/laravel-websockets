<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Определяет команды для планировщика задач
     */
    protected function schedule(Schedule $schedule): void
    {
        //
    }

    /**
     * Регистрирует консольные команды для приложения
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}

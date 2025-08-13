<?php

use Illuminate\Foundation\Inspiring;
use App\Console\Commands\SendMessages;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendMessages::class)->daily();

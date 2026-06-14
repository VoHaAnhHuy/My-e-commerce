<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Dọn dẹp guest cart hết hạn — chạy mỗi ngày lúc 2:00 AM
Schedule::command('carts:clean-expired')->dailyAt('02:00');

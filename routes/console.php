<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('check:staff', function () {
    $this->info("Staff count: " . \App\Models\Staff::count());
    foreach (\App\Models\Staff::take(10)->get() as $s) {
        $pwd = \Illuminate\Support\Facades\Hash::check('password123', $s->password) ? 'OK' : 'FAIL';
        $this->line("{$s->email} | {$s->role} | active:{$s->is_active} | pwd:{$pwd}");
    }
});

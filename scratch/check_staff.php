<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->boot();

echo "Staff count: " . \App\Models\Staff::count() . PHP_EOL;
foreach (\App\Models\Staff::take(10)->get() as $s) {
    $pwdCheck = \Illuminate\Support\Facades\Hash::check('password123', $s->password) ? 'matches' : 'FAILED';
    echo "{$s->email} | {$s->role} | active:{$s->is_active} | ward_id:{$s->ward_id} | palika_id:{$s->palika_id} | pwd:{$pwdCheck}" . PHP_EOL;
}

<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

echo "=== DIAGNOSTIC CHECK ===\n";

// 1. Check all staff users in database
$staffCount = Staff::count();
echo "Total staff in DB: {$staffCount}\n";

$rolesToTest = [
    'chair@ward32.gov.np',
    'secretary@ward32.gov.np',
    'clerk@ward32.gov.np',
    'admin.kmc@wardsewa.gov.np',
    'admin@kathmandu.gov.np',
    'admin.ktm@wardsewa.gov.np',
    'superadmin@wardsewa.gov.np'
];

foreach ($rolesToTest as $email) {
    $staff = Staff::where('email', $email)->first();
    if (!$staff) {
        echo "❌ [NOT FOUND] {$email}\n";
    } else {
        $passMatch = Hash::check('password123', $staff->password);
        echo "✓ [OK] {$email} | Role: {$staff->role} | Active: " . ($staff->is_active ? 'YES' : 'NO') . " | Pass match: " . ($passMatch ? 'YES' : 'NO') . "\n";
    }
}

// 2. Test rendering all 4 dashboard views directly
echo "\n=== TESTING DASHBOARD RENDERS ===\n";

$testCases = [
    'Super Admin' => 'superadmin@wardsewa.gov.np',
    'District Admin' => 'admin.ktm@wardsewa.gov.np',
    'Local Govt Admin' => 'admin.kmc@wardsewa.gov.np',
    'Ward Chair' => 'chair@ward32.gov.np',
];

foreach ($testCases as $label => $email) {
    try {
        $staff = Staff::where('email', $email)->first();
        Auth::guard('staff')->setUser($staff);
        
        $controller = new \App\Http\Controllers\Staff\DashboardController();
        $response = $controller->index();
        
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            echo "✓ {$label} ({$email}) -> Redirects properly to: " . $response->getTargetUrl() . "\n";
            
            // Now test target controller
            if (str_contains($response->getTargetUrl(), 'superadmin')) {
                $ctrl = new \App\Http\Controllers\Staff\SuperAdminController();
                $view = $ctrl->dashboard();
                $rendered = $view->render();
                echo "   ✓ Super Admin Dashboard View rendered successfully (" . strlen($rendered) . " bytes)\n";
            } elseif (str_contains($response->getTargetUrl(), 'district')) {
                $ctrl = new \App\Http\Controllers\Staff\DistrictAdminController();
                $view = $ctrl->dashboard();
                $rendered = $view->render();
                echo "   ✓ District Admin Dashboard View rendered successfully (" . strlen($rendered) . " bytes)\n";
            } elseif (str_contains($response->getTargetUrl(), 'localgovt')) {
                $ctrl = new \App\Http\Controllers\Staff\LocalGovtAdminController();
                $view = $ctrl->dashboard();
                $rendered = $view->render();
                echo "   ✓ Local Govt Admin Dashboard View rendered successfully (" . strlen($rendered) . " bytes)\n";
            }
        } elseif ($response instanceof \Illuminate\View\View) {
            $rendered = $response->render();
            echo "✓ {$label} ({$email}) -> Ward Dashboard View rendered successfully (" . strlen($rendered) . " bytes)\n";
        }
    } catch (\Throwable $e) {
        echo "❌ {$label} ({$email}) ERROR: " . $e->getMessage() . "\n";
        echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}

echo "\nDIAGNOSTIC COMPLETE.\n";

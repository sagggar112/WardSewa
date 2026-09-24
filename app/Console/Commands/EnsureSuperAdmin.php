<?php

namespace App\Console\Commands;

use App\Models\Staff;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsureSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wardsewa:ensure-admin
                            {--password= : Password to set for the super admin accounts (default: password123)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Guarantee the WardSewa super administrator accounts exist and can log in. Idempotent and independent of the geography seeders.';

    /**
     * The super administrator accounts this command maintains.
     * Kept in sync with the top of database/seeders/StaffSeeder.php.
     *
     * @var array<int, array<string, string>>
     */
    private array $admins = [
        [
            'email' => 'superadmin@wardsewa.gov.np',
            'name' => 'WardSewa Master Super Administrator',
            'phone' => '9800000001',
            'designation' => 'Chief Technology & Governance Officer',
        ],
        [
            'email' => 'admin@wardsewa.gov.np',
            'name' => 'Central System Administrator',
            'phone' => '9800000002',
            'designation' => 'Senior System Architect',
        ],
    ];

    public function handle(): int
    {
        $password = (string) ($this->option('password') ?: 'password123');

        foreach ($this->admins as $data) {
            $staff = Staff::where('email', $data['email'])->first();

            if (! $staff) {
                Staff::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($password),
                    'district_id' => null,
                    'palika_id' => null,
                    'ward_id' => null,
                    'role' => 'super_admin',
                    'designation' => $data['designation'],
                    'is_active' => true,
                ]);
                $this->info("Created super admin: {$data['email']}");
                continue;
            }

            // Account exists: repair anything that would block login, but do NOT
            // clobber a working password an operator may have deliberately changed.
            $updates = [];

            if (! $staff->isSuperAdmin()) {
                $updates['role'] = 'super_admin';
            }

            if (! $staff->is_active) {
                $updates['is_active'] = true;
            }

            if (! Hash::check($password, $staff->password)) {
                $updates['password'] = Hash::make($password);
            }

            if ($updates !== []) {
                $staff->update($updates);
                $this->info("Repaired super admin: {$data['email']} (" . implode(', ', array_keys($updates)) . ')');
            } else {
                $this->line("OK, no changes needed: {$data['email']}");
            }
        }

        $this->info('Super administrator accounts verified.');

        return self::SUCCESS;
    }
}

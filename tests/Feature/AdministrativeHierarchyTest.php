<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Citizen;
use App\Models\Palika;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Ward;
use Database\Seeders\BillerSeeder;
use Database\Seeders\CitizenSeeder;
use Database\Seeders\DistrictSeeder;
use Database\Seeders\PalikaSeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\ServiceTypeSeeder;
use Database\Seeders\StaffSeeder;
use Database\Seeders\WardSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministrativeHierarchyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed core geographic and staff data
        $this->seed([
            ProvinceSeeder::class,
            DistrictSeeder::class,
            PalikaSeeder::class,
            WardSeeder::class,
            ServiceTypeSeeder::class,
            StaffSeeder::class,
            CitizenSeeder::class,
            BillerSeeder::class,
        ]);
    }

    public function test_public_pages_load_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('WardSewa');

        $noticesResponse = $this->get('/notices');
        $noticesResponse->assertStatus(200);
    }

    public function test_super_admin_dashboard_and_modules(): void
    {
        $superAdmin = Staff::where('email', 'superadmin@wardsewa.gov.np')->first();
        $this->assertNotNull($superAdmin);

        // Test login redirect to Super Admin dashboard
        $response = $this->post('/staff/login', [
            'email' => 'superadmin@wardsewa.gov.np',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('staff.superadmin.dashboard'));

        // Test authenticated access to Super Admin pages
        $this->actingAs($superAdmin, 'staff')
            ->get(route('staff.superadmin.dashboard'))
            ->assertStatus(200)
            ->assertSee('Super Admin');

        $this->actingAs($superAdmin, 'staff')
            ->get(route('staff.superadmin.geography'))
            ->assertStatus(200)
            ->assertSee('Kathmandu')
            ->assertSee('Lalitpur')
            ->assertSee('Bhaktapur');

        $this->actingAs($superAdmin, 'staff')
            ->get(route('staff.superadmin.admins'))
            ->assertStatus(200);

        $this->actingAs($superAdmin, 'staff')
            ->get(route('staff.superadmin.audit-logs'))
            ->assertStatus(200);
    }

    public function test_district_admin_dashboard_and_scoping(): void
    {
        $districtAdmin = Staff::where('email', 'admin.ktm@wardsewa.gov.np')->first();
        $this->assertNotNull($districtAdmin);

        $response = $this->post('/staff/login', [
            'email' => 'admin.ktm@wardsewa.gov.np',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('staff.district.dashboard'));

        $this->actingAs($districtAdmin, 'staff')
            ->get(route('staff.district.dashboard'))
            ->assertStatus(200)
            ->assertSee('District Admin');

        $this->actingAs($districtAdmin, 'staff')
            ->get(route('staff.district.palikas'))
            ->assertStatus(200)
            ->assertSee('Kathmandu Metropolitan City');
    }

    public function test_local_govt_admin_dashboard_and_scoping(): void
    {
        $localGovtAdmin = Staff::where('email', 'admin.kmc@wardsewa.gov.np')->first();
        $this->assertNotNull($localGovtAdmin);

        $response = $this->post('/staff/login', [
            'email' => 'admin.kmc@wardsewa.gov.np',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('staff.localgovt.dashboard'));

        $this->actingAs($localGovtAdmin, 'staff')
            ->get(route('staff.localgovt.dashboard'))
            ->assertStatus(200)
            ->assertSee('Local Govt Admin');

        $this->actingAs($localGovtAdmin, 'staff')
            ->get(route('staff.localgovt.wards'))
            ->assertStatus(200)
            ->assertSee('32');
    }

    public function test_ward_staff_dashboard_and_appointments(): void
    {
        $wardChair = Staff::where('email', 'chair@ward32.gov.np')->first();
        $this->assertNotNull($wardChair);

        $response = $this->post('/staff/login', [
            'email' => 'chair@ward32.gov.np',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('staff.dashboard'));

        $this->actingAs($wardChair, 'staff')
            ->get(route('staff.dashboard'))
            ->assertStatus(200);

        $this->actingAs($wardChair, 'staff')
            ->get(route('staff.appointments.index'))
            ->assertStatus(200);
    }

    public function test_citizen_appointment_booking_flow(): void
    {
        $citizen = Citizen::first();
        $this->assertNotNull($citizen);

        $serviceType = ServiceType::first();

        // 1. Visit appointment creation form
        $this->actingAs($citizen, 'citizen')
            ->get(route('citizen.appointments.create'))
            ->assertStatus(200)
            ->assertSee('वडा कार्यालय भ्रमण समय तालिका');

        // 2. Submit appointment booking
        $postResponse = $this->actingAs($citizen, 'citizen')
            ->post(route('citizen.appointments.store'), [
                'purpose' => 'कागजात प्रमाणीकरण र हस्ताक्षर',
                'service_type_id' => $serviceType->id,
                'appointment_date' => now()->addDays(2)->format('Y-m-d'),
                'time_slot' => '11:00 AM - 12:00 PM',
                'remarks' => 'आवश्यक सक्कल प्रमाण लिएर आउनेछु।',
            ]);

        $postResponse->assertRedirect(route('citizen.appointments.index'));

        // 3. Verify appointment stored in database
        $this->assertDatabaseHas('appointments', [
            'citizen_id' => $citizen->id,
            'purpose' => 'कागजात प्रमाणीकरण र हस्ताक्षर',
            'time_slot' => '11:00 AM - 12:00 PM',
            'status' => 'scheduled',
        ]);

        // 4. Verify citizen appointments list
        $this->actingAs($citizen, 'citizen')
            ->get(route('citizen.appointments.index'))
            ->assertStatus(200)
            ->assertSee('11:00 AM - 12:00 PM');
    }

    public function test_all_138_kathmandu_wards_are_active_with_chairperson_accounts(): void
    {
        $palikas = Palika::whereHas('district', fn($q) => $q->where('code', 'KTM'))->get();
        $this->assertCount(11, $palikas);

        $totalWards = 0;
        foreach ($palikas as $palika) {
            $wards = Ward::where('palika_id', $palika->id)->get();
            $totalWards += $wards->count();

            foreach ($wards as $ward) {
                // Ensure chairperson exists for this ward
                $chair = Staff::where('ward_id', $ward->id)->where('role', 'ward_chair')->first();
                $this->assertNotNull($chair, "Ward {$ward->ward_number} in Palika {$palika->name_en} should have an active chair.");
                $this->assertTrue($chair->is_active);
                $this->assertEquals($palika->id, $chair->palika_id);
            }
        }

        $this->assertEquals(138, $totalWards);

        // Test login with both primary format and alias format
        // 1. Chandragiri Ward 1 primary login
        $resp1 = $this->post('/staff/login', [
            'email' => 'chair.cgm1@wardsewa.gov.np',
            'password' => 'password123',
        ]);
        $resp1->assertRedirect(route('staff.dashboard'));

        // 2. Budhanilkantha Ward 5 alias login
        $this->post(route('staff.logout'));

        $resp2 = $this->post('/staff/login', [
            'email' => 'chair@bnm5.gov.np',
            'password' => 'password123',
        ]);
        $resp2->assertRedirect(route('staff.dashboard'));
    }
}

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
            ->assertSee(__('Local Govt Admin'));

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

    public function test_super_admin_can_add_districts_and_palikas_with_auto_provisioning(): void
    {
        $superAdmin = Staff::where('role', 'super_admin')->first();
        $this->assertNotNull($superAdmin);

        // 1. Super Admin adds a new District
        $province = \App\Models\Province::first();
        $districtResponse = $this->actingAs($superAdmin, 'staff')
            ->post(route('staff.superadmin.districts.store'), [
                'province_id' => $province->id,
                'name_en' => 'New Himalayan District',
                'name_ne' => 'नयाँ हिमाली जिल्ला',
                'code' => 'NHD',
                'admin_name' => 'NHD DCC Officer',
            ]);

        $districtResponse->assertRedirect(route('staff.superadmin.geography'));
        $this->assertDatabaseHas('districts', [
            'code' => 'NHD',
            'name_en' => 'New Himalayan District',
        ]);
        $this->assertDatabaseHas('staff', [
            'email' => 'admin.nhd@wardsewa.gov.np',
            'role' => 'district_admin',
        ]);

        $newDistrict = \App\Models\District::where('code', 'NHD')->first();

        // 2. Super Admin adds a new Local Government (e.g. Pokhara Metropolitan City)
        $palikaResponse = $this->actingAs($superAdmin, 'staff')
            ->post(route('staff.superadmin.palikas.store'), [
                'district_id' => $newDistrict->id,
                'name_en' => 'Pokhara Metropolitan City',
                'name_ne' => 'पोखरा महानगरपालिका',
                'type' => 'metropolitan',
                'code' => 'POK',
            ]);

        $palikaResponse->assertRedirect(route('staff.superadmin.geography'));
        $this->assertDatabaseHas('palikas', [
            'code' => 'POK',
            'type' => 'metropolitan',
            'name_en' => 'Pokhara Metropolitan City',
        ]);

        // Auto-provisioned Municipal Admin
        $this->assertDatabaseHas('staff', [
            'email' => 'admin.pok@wardsewa.gov.np',
            'role' => 'local_government_admin',
        ]);

        // Verify audit log recorded
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'create_palika',
        ]);
    }

    public function test_municipal_admin_can_add_wards_strictly_within_own_area(): void
    {
        $kmcAdmin = Staff::where('email', 'admin.kmc@wardsewa.gov.np')->first();
        $this->assertNotNull($kmcAdmin);
        $kmc = $kmcAdmin->palika;

        // 1. KMC Admin adds Ward 33 to Kathmandu Metro
        $response = $this->actingAs($kmcAdmin, 'staff')
            ->post(route('staff.localgovt.wards.store'), [
                'ward_number' => 33,
                'office_address' => 'New Area, Kathmandu',
                'office_phone' => '01-4609999',
                'office_email' => 'ward33@kathmandu.gov.np',
                'chairperson_name' => 'Gopal Krishna Shrestha',
                'chairperson_phone' => '9851033333',
            ]);

        $response->assertRedirect(route('staff.localgovt.wards'));
        $this->assertDatabaseHas('wards', [
            'palika_id' => $kmc->id,
            'ward_number' => 33,
            'office_address' => 'New Area, Kathmandu',
        ]);

        // Auto-provisioned Ward Chair
        $this->assertDatabaseHas('staff', [
            'email' => 'chair.kmc33@wardsewa.gov.np',
            'name' => 'Gopal Krishna Shrestha',
            'role' => 'ward_chair',
            'ward_id' => Ward::where('palika_id', $kmc->id)->where('ward_number', 33)->first()->id,
        ]);

        // 2. Duplicate ward number in same palika should fail validation
        $duplicateResponse = $this->actingAs($kmcAdmin, 'staff')
            ->post(route('staff.localgovt.wards.store'), [
                'ward_number' => 33,
                'office_address' => 'Duplicate Location',
            ]);

        $duplicateResponse->assertSessionHasErrors(['ward_number']);
    }

    public function test_ward_chair_can_create_and_edit_secretary_and_clerk(): void
    {
        $wardChair = Staff::where('email', 'chair@ward32.gov.np')->first();
        $this->assertNotNull($wardChair);
        $ward = $wardChair->ward;

        // 1. Visit Ward Team Page
        $this->actingAs($wardChair, 'staff')
            ->get(route('staff.team.index'))
            ->assertStatus(200)
            ->assertSee('वडा कर्मचारी तथा कार्यविभाजन');

        // 2. Ward Chair adds a new Secretary
        $secResponse = $this->actingAs($wardChair, 'staff')
            ->post(route('staff.team.store'), [
                'name' => 'Bishnu Kumar Adhikari',
                'email' => 'sec.test32@wardsewa.gov.np',
                'phone' => '9841234567',
                'role' => 'secretary',
                'designation' => 'वडा सचिव (Nayab Subba)',
                'password' => 'password123',
            ]);

        $secResponse->assertRedirect(route('staff.team.index'));
        $this->assertDatabaseHas('staff', [
            'email' => 'sec.test32@wardsewa.gov.np',
            'role' => 'secretary',
            'ward_id' => $ward->id,
            'palika_id' => $ward->palika_id,
        ]);

        $secretary = Staff::where('email', 'sec.test32@wardsewa.gov.np')->first();

        // 3. Ward Chair adds a Front Desk Clerk
        $clerkResponse = $this->actingAs($wardChair, 'staff')
            ->post(route('staff.team.store'), [
                'name' => 'Gita Kumari Shrestha',
                'email' => 'clerk.test32@wardsewa.gov.np',
                'phone' => '9849876543',
                'role' => 'clerk',
                'designation' => 'फ्रन्ट डेस्क सहायक (Kharidar)',
                'password' => 'password123',
            ]);

        $clerkResponse->assertRedirect(route('staff.team.index'));
        $this->assertDatabaseHas('staff', [
            'email' => 'clerk.test32@wardsewa.gov.np',
            'role' => 'clerk',
            'ward_id' => $ward->id,
        ]);

        // 4. Ward Chair edits the Secretary's details
        $updateResponse = $this->actingAs($wardChair, 'staff')
            ->put(route('staff.team.update', $secretary->id), [
                'name' => 'Bishnu Kumar Adhikari (Promoted)',
                'phone' => '9841999999',
                'designation' => 'वरिष्ठ वडा सचिव',
                'is_active' => 1,
            ]);

        $updateResponse->assertRedirect(route('staff.team.index'));
        $this->assertDatabaseHas('staff', [
            'id' => $secretary->id,
            'name' => 'Bishnu Kumar Adhikari (Promoted)',
            'phone' => '9841999999',
            'designation' => 'वरिष्ठ वडा सचिव',
        ]);

        // Verify Audit Log recorded
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'create_ward_staff',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'update_ward_staff',
        ]);
    }

    public function test_municipal_admin_can_manage_ward_staff_in_own_palika(): void
    {
        $kmcAdmin = Staff::where('email', 'admin.kmc@wardsewa.gov.np')->first();
        $this->assertNotNull($kmcAdmin);
        $ward32 = Ward::where('palika_id', $kmcAdmin->palika_id)->where('ward_number', 32)->first();

        // 1. Municipal Admin views Ward 32 Staff Directory
        $this->actingAs($kmcAdmin, 'staff')
            ->get(route('staff.localgovt.wards.staff', $ward32->id))
            ->assertStatus(200)
            ->assertSee('वडा कर्मचारी तथा पद विवरण तालिका');

        // 2. Municipal Admin creates a staff member for Ward 32
        $addResponse = $this->actingAs($kmcAdmin, 'staff')
            ->post(route('staff.localgovt.wards.staff.store', $ward32->id), [
                'name' => 'Municipal Assigned Officer',
                'email' => 'officer.w32@wardsewa.gov.np',
                'phone' => '9851122334',
                'role' => 'secretary',
                'designation' => 'कार्यवाहक सचिव',
            ]);

        $addResponse->assertRedirect(route('staff.localgovt.wards.staff', $ward32->id));
        $this->assertDatabaseHas('staff', [
            'email' => 'officer.w32@wardsewa.gov.np',
            'ward_id' => $ward32->id,
            'role' => 'secretary',
        ]);

        $newOfficer = Staff::where('email', 'officer.w32@wardsewa.gov.np')->first();

        // 3. Municipal Admin updates staff details
        $editResponse = $this->actingAs($kmcAdmin, 'staff')
            ->put(route('staff.localgovt.wards.staff.update', ['id' => $ward32->id, 'staffId' => $newOfficer->id]), [
                'name' => 'Municipal Assigned Officer (Updated)',
                'phone' => '9851122339',
                'designation' => 'शाखा अधिकृत',
                'role' => 'secretary',
                'is_active' => 1,
            ]);

        $editResponse->assertRedirect(route('staff.localgovt.wards.staff', $ward32->id));
        $this->assertDatabaseHas('staff', [
            'id' => $newOfficer->id,
            'name' => 'Municipal Assigned Officer (Updated)',
            'phone' => '9851122339',
        ]);
    }

    public function test_role_tailored_responsibility_dashboards(): void
    {
        $wardChair = Staff::where('email', 'chair@ward32.gov.np')->first();
        $secretary = Staff::where('email', 'secretary@ward32.gov.np')->first();
        $clerk = Staff::where('email', 'clerk@ward32.gov.np')->first();

        // 1. Ward Chairperson Dashboard shows Executive suite
        $this->actingAs($wardChair, 'staff')
            ->get(route('staff.dashboard'))
            ->assertStatus(200)
            ->assertSee('वडा अध्यक्ष कार्यजिम्मेवारी')
            ->assertSee('अन्तिम प्रमाणीकरण, सिफारिस निर्णय तथा वडा सुपरिवेक्षण');

        // 2. Ward Secretary Dashboard shows Scrutiny & Review suite
        $this->actingAs($secretary, 'staff')
            ->get(route('staff.dashboard'))
            ->assertStatus(200)
            ->assertSee('वडा सचिव कार्यजिम्मेवारी')
            ->assertSee('कागजात रुजु, सिफारिस तयारी तथा प्राविधिक जाँच शाखा');

        // 3. Front Desk Clerk Dashboard shows Intake & Counter Support suite
        $this->actingAs($clerk, 'staff')
            ->get(route('staff.dashboard'))
            ->assertStatus(200)
            ->assertSee('सहायक कर्मचारी कार्यजिम्मेवारी')
            ->assertSee('नागरिक सोधपुछ, टोकन व्यवस्थापन तथा दर्ता सहायता');
    }
}


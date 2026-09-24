<?php

namespace Tests\Feature;

use App\Models\Citizen;
use App\Models\Ward;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitizenDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_citizen_redirected_to_login(): void
    {
        $response = $this->get(route('citizen.dashboard'));
        $response->assertRedirect(route('citizen.login'));
    }

    public function test_authenticated_citizen_can_view_advanced_dashboard(): void
    {
        $citizen = Citizen::where('phone', '9841000000')->first() ?? Citizen::first();
        $this->assertNotNull($citizen);

        $response = $this->actingAs($citizen, 'citizen')->get(route('citizen.dashboard'));
        $response->assertStatus(200);

        // Check Digital Nagarik elements
        $response->assertSee($citizen->full_name);
        $response->assertSee(__('Digital Citizen Card'));
        $response->assertSee(__('Verified Citizen'));
        $response->assertSee(__('Ward Office & Jurisdiction'));
        $response->assertSee(__('Pending Reviews'));
        $response->assertSee(__('Approved Recommendations'));
        $response->assertSee(__('Upcoming Appointments'));
        $response->assertSee(__('Your Ward Representatives'));
        $response->assertSee(__('Digital Services Available from Ward Office'));
    }

    public function test_dashboard_renders_monolingual_in_both_locales(): void
    {
        $citizen = Citizen::where('phone', '9841000000')->first() ?? Citizen::first();

        // English test
        $responseEn = $this->withSession(['locale' => 'en'])
            ->actingAs($citizen, 'citizen')
            ->get(route('citizen.dashboard'));
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Digital Citizen Card');
        $responseEn->assertSee('Verified Citizen');

        // Nepali test
        $responseNe = $this->withSession(['locale' => 'ne'])
            ->actingAs($citizen, 'citizen')
            ->get(route('citizen.dashboard'));
        $responseNe->assertStatus(200);
        $responseNe->assertSee('नागरिक डिजिटल परिचय-पत्र');
        $responseNe->assertSee('प्रमाणित नागरिक');
    }

    public function test_welcome_page_renders_monolingual(): void
    {
        $responseEn = $this->withSession(['locale' => 'en'])->get('/');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Citizen Login');

        $responseNe = $this->withSession(['locale' => 'ne'])->get('/');
        $responseNe->assertStatus(200);
        $responseNe->assertSee('नागरिक लगइन');
    }
}

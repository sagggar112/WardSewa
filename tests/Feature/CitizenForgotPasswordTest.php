<?php

namespace Tests\Feature;

use App\Models\Citizen;
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
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CitizenForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_forgot_password_page_loads_successfully(): void
    {
        $response = $this->get(route('citizen.password.request'));
        $response->assertStatus(200);
        $response->assertSee(__('Forgot Password?'));
    }

    public function test_citizen_can_reset_password_with_valid_details(): void
    {
        $citizen = Citizen::first();
        $this->assertNotNull($citizen);

        $response = $this->post(route('citizen.password.update'), [
            'login' => $citizen->phone,
            'verification_field' => $citizen->full_name,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('citizen.login'));
        $response->assertSessionHas('success');

        $citizen->refresh();
        $this->assertTrue(Hash::check('newpassword123', $citizen->password));
    }

    public function test_citizen_cannot_reset_password_with_incorrect_name_or_citizenship(): void
    {
        $citizen = Citizen::first();
        $this->assertNotNull($citizen);

        $response = $this->from(route('citizen.password.request'))
            ->post(route('citizen.password.update'), [
                'login' => $citizen->phone,
                'verification_field' => 'Random Wrong Name',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('citizen.password.request'));
        $response->assertSessionHasErrors(['verification_field']);
    }
}

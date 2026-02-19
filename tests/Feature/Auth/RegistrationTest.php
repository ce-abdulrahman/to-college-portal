<?php

namespace Tests\Feature\Auth;

use App\Models\Province;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'status' => 1,
        ]);

        Province::query()->create([
            'name' => 'هەولێر',
            'name_en' => 'Erbil',
            'status' => 1,
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'code' => '123456',
            'phone' => '07500000000',
            'password' => 'password',
            'mark' => 90,
            'province' => 'هەولێر',
            'type' => 'زانستی',
            'gender' => 'نێر',
            'year' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('auth.register-waiting');

        $this->assertDatabaseHas('users', [
            'code' => '123456',
            'role' => 'student',
            'status' => 0,
        ]);

        $user = User::query()->where('code', '123456')->firstOrFail();
        $this->assertDatabaseHas('students', [
            'user_id' => $user->id,
            'province' => 'هەولێر',
            'status' => 0,
        ]);
    }
}

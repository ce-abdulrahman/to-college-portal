<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createStudentUser(): User
    {
        $user = User::factory()->create([
            'role' => 'student',
            'status' => 1,
        ]);

        Student::query()->create([
            'user_id' => $user->id,
            'mark' => 90,
            'province' => 'هەولێر',
            'type' => 'زانستی',
            'gender' => 'نێر',
            'year' => 1,
            'referral_code' => '0',
            'status' => 1,
            'ai_rank' => 0,
            'gis' => 0,
            'all_departments' => 0,
        ]);

        return $user;
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = $this->createStudentUser();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->createStudentUser();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'phone' => '07500000000',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('07500000000', $user->phone);
    }

    public function test_profile_update_keeps_code_unchanged(): void
    {
        $user = $this->createStudentUser();
        $originalCode = $user->code;

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'phone' => $user->phone,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertSame($originalCode, $user->refresh()->code);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = $this->createStudentUser();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = $this->createStudentUser();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}

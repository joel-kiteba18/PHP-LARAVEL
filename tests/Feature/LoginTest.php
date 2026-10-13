<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_sees_login_page_and_is_redirected_from_private_pages(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Se connecter');

        $this->get(route('clients.index'))
            ->assertRedirect(route('login'));

        $this->get(route('produits.index'))
            ->assertRedirect(route('login'));

        $this->get(route('commandes.index'))
            ->assertRedirect(route('login'));
    }

    public function test_valid_credentials_authenticate_user_and_redirect_to_intended_page(): void
    {
        $user = User::factory()->create([
            'email' => 'login-success@example.test',
            'password' => Hash::make('StrongPass123!'),
        ]);

        $this->get(route('produits.index'));

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'StrongPass123!',
            'remember' => '1',
        ])->assertRedirect(route('produits.index'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('produits.index'))->assertOk();
    }

    public function test_invalid_credentials_return_generic_validation_error(): void
    {
        User::factory()->create([
            'email' => 'login-failure@example.test',
            'password' => Hash::make('StrongPass123!'),
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'login-failure@example.test',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out_and_session_is_ended(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post(route('login.store'), [
                'email' => 'limited@example.test',
                'password' => 'invalid-password',
            ])->assertSessionHasErrors('email');
        }

        $this->post(route('login.store'), [
            'email' => 'limited@example.test',
            'password' => 'invalid-password',
        ])->assertTooManyRequests();
    }

    public function test_account_can_be_created_from_the_secure_console_command(): void
    {
        $this->artisan('app:create-user')
            ->expectsQuestion('Nom', 'Administrateur')
            ->expectsQuestion('Adresse e-mail', 'new-admin@example.test')
            ->expectsQuestion('Mot de passe (12 caractères minimum)', 'StrongPass123!')
            ->expectsQuestion('Confirmer le mot de passe', 'StrongPass123!')
            ->assertSuccessful();

        $user = User::query()->where('email', 'new-admin@example.test')->firstOrFail();

        $this->assertSame('Administrateur', $user->name);
        $this->assertTrue(Hash::check('StrongPass123!', $user->password));
    }
}

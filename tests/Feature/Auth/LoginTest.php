<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);
});

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

describe('login validation', function () {
    it('memerlukan email', function () {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    it('memerlukan password', function () {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    });

    it('memvalidasi format email', function () {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });
});

describe('login behavior', function () {
    it('dapat login dengan kredensial yang valid', function () {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    });

    it('menolak login dengan email yang tidak valid', function () {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    it('menolak login dengan password yang salah', function () {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });
});

describe('logout behavior', function () {
    it('dapat logout dengan sukses', function () {
        $this->actingAs($this->user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    });
});

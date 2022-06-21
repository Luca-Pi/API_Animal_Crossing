<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        /**
         * GET TOKEN : /api/token
         */
        $xsrfToken = $this->get('/api/token')->getContent();
        $this->assertNotNull($xsrfToken);

        /**
         * POST REGISTER : /api/register
         */
        $response = $this->withHeaders([
            'XSRF-TOKEN' => $xsrfToken,
        ])->post('/api/register', [
            'email' => 'logan.lesaux@gmail.com',
            'username' => 'loganls',
            'password' => 'logoon',
            'password_confirmation' => 'logoon',
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $token = $content['api_token'];

        /**
         * GET USER : /api/me
         */
        $response = $this->get('/api/me?api_token=' . $token);
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertEquals('logan.lesaux@gmail.com', $content['email']);
        $this->assertEquals('loganls', $content['username']);
    }
}

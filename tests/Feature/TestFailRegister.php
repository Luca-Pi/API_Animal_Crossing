<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Test_FailRegister_ extends TestCase
{
    use RefreshDatabase;

    public function passwordMissMatchFail()
    {
        $xsrfToken = $this->getToken();

        /**
         * POST REGISTER : /api/register
         * fail miss match password confirmation
         */
        $response = $this->withHeaders([
            'XSRF-TOKEN' => $xsrfToken,
        ])->post('/api/register', [
            'email' => 'logan.lesaux@gmail.com',
            'username' => 'loganls',
            'password' => 'logoon',
            'password_confirmation' => 'logon',
        ]);
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $token = $content['api_token'];

    }

    /**
     * @param $xsrfToken
     * @return void
     */
    public function mailFail($xsrfToken): void
    {
        $xsrfToken = $this->getToken();

        /**
         * POST REGISTER : /api/register
         * fail mail error
         */
        $response = $this->withHeaders([
            'XSRF-TOKEN' => $xsrfToken,
        ])->post('/api/register', [
            'email' => 'logan.lesaux@gmail',
            'username' => 'loganls',
            'password' => 'logoon',
            'password_confirmation' => 'logoon',
        ]);

        $response->assertStatus(402);
        $content = json_decode($response->getContent(), true);
        $token = $content['api_token'];
    }

    /**
     * @param $xsrfToken
     * @return void
     */
    public function usernameFail($xsrfToken): void
    {
        $xsrfToken = $this->getToken();

        /**
         * POST REGISTER : /api/register
         * fail username error
         */
        $response = $this->withHeaders([
            'XSRF-TOKEN' => $xsrfToken,
        ])->post('/api/register', [
            'email' => 'logan.lesaux@gmail.com',
            'username' => 'l',
            'password' => 'logoon',
            'password_confirmation' => 'logoon',
        ]);

        $response->assertStatus(402);
        $content = json_decode($response->getContent(), true);
        $token = $content['api_token'];
    }

    /**
     * @param $xsrfToken
     * @return void
     */
    public function passwordEmptyFail($xsrfToken): void
    {
        $xsrfToken = $this->getToken();

        /**
         * POST REGISTER : /api/register
         * fail password empty
         */
        $response = $this->withHeaders([
            'XSRF-TOKEN' => $xsrfToken,
        ])->post('/api/register', [
            'email' => 'logan.lesaux@gmail.com',
            'username' => 'loganls',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(500);
        $content = json_decode($response->getContent(), true);
        $token = $content['api_token'];
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        $xsrfToken = $this->getToken();

        /**
         * GET TOKEN : /api/token
         */
        $xsrfToken = $this->get('/api/token')->getContent();
        $this->assertNotNull($xsrfToken);
        return $xsrfToken;
    }
}

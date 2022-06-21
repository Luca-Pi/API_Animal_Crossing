<?php

namespace Tests\Feature;

use App\Models\Fishes;
use App\Models\HasFish;
use App\Models\Insect;
use App\Models\SetFurniture;
use App\Models\User;
use Database\Factories\UserFactory;
use Database\Seeders\FishesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddItemToCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('db:seed');
    }

    public function test_add_fish_to_collection()
    {
        $user = User::factory()->createOne();
        $userAttributes = $user->getAttributes();

        $token = $this->login($userAttributes['email'], 'password');

        $response = $this->post(
            '/api/has-fish-user?api_token='. $token .
            '&user_id=' . $userAttributes['id'] .
            '&fish_id=1'
        );

        $response->assertStatus(200);

        $hasFish = HasFish::query()
            ->where('fish_id', '=', 1)
            ->where('user_id', '=', $userAttributes['id'])
            ->get()
            ->first()
        ;

        $this->assertNotEmpty($hasFish);
    }

    private function login(string $email, string $password): string
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
        ])->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        return $content['api_token'];
    }
}

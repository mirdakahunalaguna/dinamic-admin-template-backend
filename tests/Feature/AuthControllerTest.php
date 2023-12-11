<?php
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_login_super_admin()
    {
        // Create a super admin user for testing
        $superAdmin = User::factory()->create([
            'email' => 'mirda_yanuar@gmail.com',
            'password' => bcrypt('secret1234'),
        ]);

        // Assign the 'super admin' role to the user
        $superAdmin->assignRole('super admin');

        // Simulate a login request
        $loginData = [
            'email' => 'mirda_yanuar@gmail.com',
            'password' => 'secret1234',
        ];

        // Act
        $response = $this->postJson(route('login'), $loginData);
        // Debugging statement
 dd($superAdmin, $response->json());
        // Assert
        $response->assertStatus(200);

        // Assert that the returned JSON contains the expected data
        $response->assertJson([
            'data' => [
                'email' => 'mirda_yanuar@gmail.com',
            ],
        ]);

        // Assert that the returned JSON structure includes a token
        $response->assertJsonStructure([
            'token',
        ]);

        // Additional assertions based on your expected behavior
    }
}



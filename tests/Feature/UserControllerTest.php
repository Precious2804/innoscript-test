<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        // Arrange: Prepare valid registration data
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Act: Make the POST request
        $response = $this->postJson(route('auth.register'), $data);

        // Assert: Check if the response is correct
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'dateCreated'
                ],
                'message',
                'status',
            ]);
    }

    public function test_login_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Prepare login data
        $data = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        // Act: Make the POST request
        $response = $this->postJson(route('auth.login'), $data);

        // Assert: Check if the response is correct
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'dateCreated'
                ],
                'message',
                'status',
            ])
            // Assert that the token is a non-empty string
            ->assertJsonPath('token', fn($token) => is_string($token) && !empty($token));
    }

    public function test_user_info()
    {
        // Arrange: Create and log in a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act: Make the GET request
        $response = $this->getJson(route('user.info'));

        // Assert: Check for success and correct user data
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'dateCreated'
                ],
                'message',
                'status',
            ]);
    }

    public function test_logout_user()
    {
        // Arrange: Create and log in a user
        $user = User::factory()->create();

        // Make sure the user is authenticated and an access token is issued
        $token = $user->createToken('Test Token')->plainTextToken; // Generate a token for the user
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ]);

        // Act: Make the POST request to logout
        $response = $this->postJson(route('user.logout'));

        // Assert: Check for success
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Logout Successful',
                'status' => 'success',
            ]);
    }

    public function test_update_password()
    {
        // Arrange: Create a user with a known password
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // Prepare data: Pass the old password as 'password123' and the new password
        $data = [
            'old_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        // Act: Make the PUT request to update the password
        $response = $this->putJson(route('user.updatePassword'), $data);

        // Assert: Check for success
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Password Updated',
                'status' => 'success',
            ]);
    }

    public function test_set_preferences()
    {
        // Arrange: Create and log in a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Prepare preferences data
        $data = [
            'preferences' => [
                [
                    'type' => 'category',
                    'value' => 'Technology'
                ],
                [
                    'type' => 'author',
                    'value' => 'John Doe'
                ],
                [
                    'type' => 'source',
                    'value' => 'BBC'
                ]
            ]
        ];

        // Act: Make the PUT request to set preferences
        $response = $this->putJson(route('user.setPreferences'), $data);

        // Assert: Check for success
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Update was Successful',
                'status' => 'success',
            ]);
    }

    public function test_get_preferences()
    {
        // Arrange: Create and log in a user with preferences
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act: Make the GET request to retrieve preferences
        $response = $this->getJson(route('user.getPreferences'));

        // Assert: Check for success and correct data structure
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['category', 'author', 'source']
                ],
                'message',
                'status',
            ]);
    }
}

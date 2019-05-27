<?php

namespace Tests\JWT4L\Feature;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use JWT4L\Token\Generator;
use Tests\JWT4L\BaseTest;

class JwtGuardTest extends BaseTest
{
    /**
     * @var Generator
     */
    private $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = $this->app->make(Generator::class);
    }

    /** @test **/
    public function it_will_respond_with_token_not_found_without_an_authorization_header()
    {
        $response = $this->get('/test-jwt-route');
        $this->assertEquals('Authorization Bearer token not found', $response->exception->getMessage());
    }

    /** @test **/
    public function it_will_run_the_configured_checks()
    {
        $response = $this->withHeader('Authorization', 'Bearer a.b.c')->get('/test-jwt-route');
        // since it should pass the structure check, the signature should check the header and fail there
        $this->assertEquals('The JWT Header is not valid', $response->exception->getMessage());
    }

    /** @test **/
    public function it_will_authenticate_a_valid_token()
    {
        $token = $this->generator->withPayload(['sub' => 1])->create();

        $response = $this->withHeader('Authorization', "Bearer {$token}")->get('/test-jwt-route');

        $this->assertEquals("JWT SUCCESS", $response->getContent());
    }

    /** @test */
    public function it_will_provide_user_info_through_guard_commands()
    {
        $token = $this->generator->withPayload(['sub' => 1])->create();

        $this->withHeader('Authorization', "Bearer {$token}")->get('/test-jwt-route');

        $this->assertInstanceOf(Authenticatable::class, Auth::user());
        $this->assertEquals(1, Auth::id());
        $this->assertEquals(false, Auth::guest());
        $this->assertEquals(true, Auth::validate(['email' => 'example@example.com', 'password' => 'test']));
    }
}
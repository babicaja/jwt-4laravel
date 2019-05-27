<?php

namespace Tests\JWT4L\Feature;

use JWT4L\Generator;
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
}
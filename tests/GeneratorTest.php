<?php

namespace Tests\JWT4L;

use JWT4L\Generator;

class GeneratorTest extends BaseTest
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
    public function it_can_generate_a_token()
    {
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJpYXQiOiIyMDEyLTEyLTIxVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDEyLTEyLTIxVDEyOjE1OjAwLjAwMDAwMFoifQ==.d4d513d6449050229d8ef73325c88d01a3707a49c5fc7c86bad5e741657c0c7b", $this->generator->create());
    }

    /** @test **/
    public function it_can_append_to_the_header_and_generate_a_token()
    {
        $this->generator->withHeader(['test' => 'claim']);
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYiLCJ0ZXN0IjoiY2xhaW0ifQ==.eyJpYXQiOiIyMDEyLTEyLTIxVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDEyLTEyLTIxVDEyOjE1OjAwLjAwMDAwMFoifQ==.020d0c1ed9840675f4a68d01f32357f866ec82fcebf62edaa6a2dcc65bad9e63", $this->generator->create());
    }

    /** @test **/
    public function it_can_replace_the_header_and_generate_a_token()
    {
        $this->generator->withHeader(['test' => 'claim'], true);
        $this->assertEquals("eyJ0ZXN0IjoiY2xhaW0ifQ==.eyJpYXQiOiIyMDEyLTEyLTIxVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDEyLTEyLTIxVDEyOjE1OjAwLjAwMDAwMFoifQ==.92a31e9c7ccb737c96bf127d3cb05ed79b6f1574c6422d82ad387d2546cc849b", $this->generator->create());
    }

    /** @test **/
    public function it_can_append_to_the_payload_and_generate_a_token()
    {
        $this->generator->withPayload(['test' => 'claim']);
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJpYXQiOiIyMDEyLTEyLTIxVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDEyLTEyLTIxVDEyOjE1OjAwLjAwMDAwMFoiLCJ0ZXN0IjoiY2xhaW0ifQ==.7c62872412b4a0a699f8d02e7015929224a569ae8b90c5a7af4bebf3c7d44ba7", $this->generator->create());
    }

    /** @test **/
    public function it_can_replace_the_payload_and_generate_a_token()
    {
        $this->generator->withPayload(['test' => 'claim'], true);
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJ0ZXN0IjoiY2xhaW0ifQ==.e2d23f571b2bbc41b42da723b3945c052a2e29feae1f9baa5ee43bab1727603d", $this->generator->create());
    }
}
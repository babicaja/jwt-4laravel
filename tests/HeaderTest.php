<?php

namespace Tests\Unit\JWT;

use App\Services\JWT\Sections\Header;
use Tests\TestCase;

class HeaderTest extends TestCase
{
    /**
     * @var Header
     */
    private $header;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        // set the algorithm type in the config
        config(['jwt.algorithm' => 'sha512']);

        $this->header = $this->app->make(Header::class);
    }

    /** @test **/
    public function it_can_encode_the_header_with_default_values()
    {
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGE1MTIifQ==', $this->header->make());
    }

    /** @test **/
    public function it_can_encode_the_header_with_appended_values()
    {
        $this->header->with(['test'=>123]);

        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGE1MTIiLCJ0ZXN0IjoxMjN9', $this->header->make());
    }

    /** @test **/
    public function it_can_encode_the_header_with_replaced_values()
    {
        $this->header->with(['test'=>123], true);

        $this->assertEquals('eyJ0ZXN0IjoxMjN9', $this->header->make());
    }

    /**
     * @test
     * @expectedException \App\Services\JWT\Exceptions\JWTAlgorithmNotSupportedException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage The test algorithm is not supported
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_if_the_algorithm_is_unsupported()
    {
        // set the algorithm type in the config
        config(['jwt.algorithm' => 'test']);

        $this->app->make(Header::class);
    }

    /** @test **/
    public function it_can_access_claims_through_magic_get()
    {
        $this->assertEquals('JWT', $this->header->typ);
    }

    /** @test **/
    public function it_can_be_json_encoded()
    {
        $this->assertJsonStringEqualsJsonString('{"typ":"JWT","alg":"sha512"}', json_encode($this->header));
    }
}

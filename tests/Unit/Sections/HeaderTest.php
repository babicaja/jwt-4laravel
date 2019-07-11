<?php

namespace Tests\JWT4L\Unit\Sections;

use JWT4L\Exceptions\JWTAlgorithmNotSupported;
use JWT4L\Sections\Header;
use Tests\JWT4L\BaseTest;
use Tests\JWT4L\Traits\CapturesOutputBuffer;

class HeaderTest extends BaseTest
{
    use CapturesOutputBuffer;

    /**
     * @var Header
     */
    private $header;

    protected function setUp(): void
    {
        parent::setUp();

        $this->header = $this->app->make(Header::class);
    }

    /** @test **/
    public function it_can_encode_the_header_with_default_values()
    {
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==', $this->header->make());
    }

    /** @test **/
    public function it_can_encode_the_header_with_appended_values()
    {
        $this->header->with(['test'=>123]);

        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYiLCJ0ZXN0IjoxMjN9', $this->header->make());
    }

    /** @test **/
    public function it_can_encode_the_header_with_replaced_values()
    {
        $this->header->with(['test'=>123], true);

        $this->assertEquals('eyJ0ZXN0IjoxMjN9', $this->header->make());
    }

    /** @test */
    public function it_will_throw_if_the_algorithm_is_unsupported()
    {
        // set the algorithm type in the config
        $this->overrideConfiguration(['jwt.algorithm' => 'test']);
        $this->expectException(JWTAlgorithmNotSupported::class);
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
        $this->assertJsonStringEqualsJsonString('{"typ":"JWT","alg":"sha256"}', json_encode($this->header));
    }
    
    /** @test **/
    public function it_will_expose_claims_on_dump()
    {
        $dump = $this->capture("var_dump", $this->header);

        $this->assertStringContainsString(Header::class, $dump);
        $this->assertStringContainsString("JWT", $dump);
        $this->assertStringContainsString("sha256", $dump);
    }
}

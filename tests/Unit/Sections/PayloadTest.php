<?php

namespace Tests\JWT4L\Unit\Sections;

use JWT4L\Sections\Payload;
use Tests\JWT4L\BaseTest;
use Tests\JWT4L\Traits\CapturesOutputBuffer;

class PayloadTest extends BaseTest
{
    use CapturesOutputBuffer;

    /**
     * @var Payload
     */
    private $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = $this->app->make(Payload::class);
    }

    /** @test **/
    public function it_can_encode_the_payload_with_default_values()
    {
        $this->assertEquals('eyJpYXQiOiIyMDEyLTEyLTIxVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDEyLTEyLTIxVDEyOjE1OjAwLjAwMDAwMFoifQ==', $this->payload->make());
    }

    /** @test **/
    public function it_can_encode_the_payload_with_appended_values()
    {
        $this->payload->with(['test'=>123]);

        $this->assertEquals('eyJpYXQiOiIyMDEyLTEyLTIxVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDEyLTEyLTIxVDEyOjE1OjAwLjAwMDAwMFoiLCJ0ZXN0IjoxMjN9', $this->payload->make());
    }

    /** @test **/
    public function it_can_encode_the_payload_with_replaced_values()
    {
        $this->payload->with(['test'=>123], true);

        $this->assertEquals('eyJ0ZXN0IjoxMjN9', $this->payload->make());
    }

    /** @test **/
    public function it_can_access_claims_through_magic_get()
    {
        $this->assertEquals('2012-12-21T12:00:00.000000Z', $this->payload->iat->toISOString());
    }

    /** @test **/
    public function it_will_set_the_default_issued_at_and_expiration_times()
    {
        $expireIn = 5;
        $this->overrideConfiguration(['jwt.expires' => $expireIn]);

        $payload = $this->app->make(Payload::class);

        $this->assertEquals($expireIn, $payload->iat->diffInMinutes($payload->exp));
    }

    /** @test **/
    public function it_can_be_json_encoded()
    {
        $this->assertJsonStringEqualsJsonString('{"iat":"2012-12-21T12:00:00.000000Z","exp":"2012-12-21T12:15:00.000000Z"}', json_encode($this->payload));
    }

    /** @test **/
    public function it_will_expose_claims_on_dump()
    {
        $dump = $this->capture("var_dump", $this->payload);

        $this->assertStringContainsString(Payload::class, $dump);
        $this->assertStringContainsString("iat", $dump);
        $this->assertStringContainsString("exp", $dump);
    }
}

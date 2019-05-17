<?php

namespace Tests\Unit\JWT;

use App\Services\JWT\Sections\Payload;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class PayloadTest extends TestCase
{
    /**
     * @var Payload
     */
    private $payload;

    /**
     * @var CarbonImmutable
     */
    private $now;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->now = CarbonImmutable::create(2019, 5, 13, 12, 0, 0);
        CarbonImmutable::setTestNow($this->now);

        // set the algorithm type in the config
        config(['jwt.expires' => '13']);

        $this->payload = $this->app->make(Payload::class);
    }

    /** @test **/
    public function it_can_encode_the_header_with_default_values()
    {
        $this->assertEquals('eyJpYXQiOiIyMDE5LTA1LTEzVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDE5LTA1LTEzVDEyOjEzOjAwLjAwMDAwMFoifQ==', $this->payload->make());
    }

    /** @test **/
    public function it_can_encode_the_header_with_appended_values()
    {
        $this->payload->with(['test'=>123]);

        $this->assertEquals('eyJpYXQiOiIyMDE5LTA1LTEzVDEyOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDE5LTA1LTEzVDEyOjEzOjAwLjAwMDAwMFoiLCJ0ZXN0IjoxMjN9', $this->payload->make());
    }

    /** @test **/
    public function it_can_encode_the_header_with_replaced_values()
    {
        $this->payload->with(['test'=>123], true);

        $this->assertEquals('eyJ0ZXN0IjoxMjN9', $this->payload->make());
    }

    /** @test **/
    public function it_can_access_claims_through_magic_get()
    {
        $this->assertEquals('2019-05-13T12:00:00.000000Z', $this->payload->iat->toISOString());
    }

    /** @test **/
    public function it_will_set_the_correct_expiration_time()
    {
        $difference = $this->now->diffInMinutes($this->payload->exp);

        $this->assertEquals(13, $difference);
    }

    /** @test **/
    public function it_can_be_json_encoded()
    {
        $this->assertJsonStringEqualsJsonString('{"iat":"2019-05-13T12:00:00.000000Z","exp":"2019-05-13T12:13:00.000000Z"}', json_encode($this->payload));
    }
}

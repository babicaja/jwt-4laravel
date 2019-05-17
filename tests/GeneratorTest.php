<?php

namespace Tests\JWT4L;

use Carbon\CarbonImmutable;
use JWT4L\Generator;

class GeneratorTest extends PackageTest
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        CarbonImmutable::setTestNow(CarbonImmutable::create(2019, 5, 15, 0, 0, 0));

        config(['jwt.algorithm' => 'sha256']);
        config(['jwt.secret' => 'generator-secret']);
        config(['jwt.expires' => '15']);

        $this->generator = $this->app->make(Generator::class);
    }

    /** @test **/
    public function it_can_generate_a_token()
    {
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJpYXQiOiIyMDE5LTA1LTE1VDAwOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDE5LTA1LTE1VDAwOjE1OjAwLjAwMDAwMFoifQ==.19cacf442ea1cdb43316f8a135ec2f1fb5825b84ecc46d4295f468604fa41345", $this->generator->create());
    }

    /** @test **/
    public function it_can_append_to_the_header_and_generate_a_token()
    {
        $this->generator->withHeader(['test' => 'claim']);
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYiLCJ0ZXN0IjoiY2xhaW0ifQ==.eyJpYXQiOiIyMDE5LTA1LTE1VDAwOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDE5LTA1LTE1VDAwOjE1OjAwLjAwMDAwMFoifQ==.644c8fb8c4eed08d82bbdebd881c3b71d793cf812e6253cf0e77db8c05cb13a8", $this->generator->create());
    }

    /** @test **/
    public function it_can_replace_the_header_and_generate_a_token()
    {
        $this->generator->withHeader(['test' => 'claim'], true);
        $this->assertEquals("eyJ0ZXN0IjoiY2xhaW0ifQ==.eyJpYXQiOiIyMDE5LTA1LTE1VDAwOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDE5LTA1LTE1VDAwOjE1OjAwLjAwMDAwMFoifQ==.1d34a354f7fa94bc7ac0dc03486d479dae03bb52581b566f31c6d20ecc1518f4", $this->generator->create());
    }

    /** @test **/
    public function it_can_append_to_the_payload_and_generate_a_token()
    {
        $this->generator->withPayload(['test' => 'claim']);
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJpYXQiOiIyMDE5LTA1LTE1VDAwOjAwOjAwLjAwMDAwMFoiLCJleHAiOiIyMDE5LTA1LTE1VDAwOjE1OjAwLjAwMDAwMFoiLCJ0ZXN0IjoiY2xhaW0ifQ==.c8e84fc5ca897f9999c75590329da1c89af4f9490053c280c8dd56afd02c1f78", $this->generator->create());
    }

    /** @test **/
    public function it_can_replace_the_payload_and_generate_a_token()
    {
        $this->generator->withPayload(['test' => 'claim'], true);
        $this->assertEquals("eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJ0ZXN0IjoiY2xhaW0ifQ==.0db8c0fc1fc72e64002a4b64ec7fbeca9a6d6069c31fe0993bb2f6f1459f6fa9", $this->generator->create());
    }
}
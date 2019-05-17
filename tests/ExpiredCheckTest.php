<?php

namespace Tests\Unit\JWT;

use App\Services\JWT\Checks\Expired;
use App\Services\JWT\Generator;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class ExpiredCheckTest extends TestCase
{
    /**
     * @var CarbonImmutable
     */
    private $testNow;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var Expired
     */
    private $check;

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $expiresIn = 15;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['jwt.expires' => $this->expiresIn]);
        config(['jwt.secret' => 'test-secret']);
        config(['jwt.algorithm' => 'sha256']);

        $this->testNow = CarbonImmutable::create(2012, 12, 21, 12, 0, 0);

        CarbonImmutable::setTestNow($this->testNow);

        $this->generator = $this->app->make(Generator::class);
        $this->check = $this->app->make(Expired::class);

        $this->token = $this->generator->create();
    }

    /**
     * @test
     * @expectedException \App\Services\JWT\Exceptions\JWTExpiredException
     */
    public function it_will_throw_a_proper_exception_if_the_token_has_expired()
    {
        Carbon::setTestNow($this->testNow->addMinutes($this->expiresIn + 1));
        $this->check->validate($this->token);
    }

    /** @test **/
    public function it_will_finish_silently_if_the_token_has_not_expired()
    {
        Carbon::setTestNow($this->testNow->addMinutes($this->expiresIn - 1));
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->assertNull($this->check->validate($this->token));
    }
}

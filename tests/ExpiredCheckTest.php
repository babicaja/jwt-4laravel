<?php

namespace Tests\JWT4L;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use JWT4L\Checks\Expired;
use JWT4L\Exceptions\JWTExpiredException;
use JWT4L\Generator;

class ExpiredCheckTest extends PackageTest
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

    /** @test */
    public function it_will_throw_a_proper_exception_if_the_token_has_expired()
    {
        Carbon::setTestNow($this->testNow->addMinutes($this->expiresIn + 1));
        $this->expectException(JWTExpiredException::class);

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

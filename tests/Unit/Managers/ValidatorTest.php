<?php

namespace Tests\JWT4L\Unit\Managers;

use Illuminate\Contracts\Container\BindingResolutionException;
use JWT4L\Checks\Structure;
use JWT4L\Exceptions\JWTAuthorizationHeaderMissing;
use JWT4L\Exceptions\JWTCheckNotValid;
use JWT4L\Exceptions\JWTNotValid;
use JWT4L\Managers\Generator;
use JWT4L\Managers\Parser;
use JWT4L\Managers\Validator;
use stdClass;
use Tests\JWT4L\BaseTest;

class ValidatorTest extends BaseTest
{
    /**
     * @var Validator
     */
    private $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setChecks([Structure::class]);
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTCheckNotValid
     */
    public function it_will_only_run_the_configured_checks_on_validation()
    {
        $this->assertInstanceOf(Parser::class, $this->validator->validate("one.two.three"));
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTCheckNotValid
     */
    public function it_will_throw_a_proper_exception_if_validation_fails()
    {
        $this->expectException(JWTNotValid::class);
        $this->validator->validate("one.two");
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTCheckNotValid
     */
    public function it_will_throw_a_proper_exception_if_a_check_is_not_valid()
    {
        $this->setChecks([stdClass::class]);
        $this->expectException(JWTCheckNotValid::class);
        $this->validator->validate("one.two.three");
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTCheckNotValid
     */
    public function it_will_use_the_bearer_token_if_the_token_is_not_provided()
    {
        $this->configure();
        $token = resolve(Generator::class)->create();
        request()->headers->add(["Authorization" => "Bearer {$token}"]);
        $this->assertInstanceOf(Parser::class, $this->validator->validate());
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTCheckNotValid
     */
    public function it_will_throw_a_proper_exception_if_the_token_is_not_provided_or_not_in_the_header()
    {
        $this->expectException(JWTAuthorizationHeaderMissing::class);
        $this->validator->validate();
    }

    public function setChecks(array $checks = []): void
    {
        $this->overrideConfiguration(['jwt.checks' => $checks]);

        $this->validator = $this->app->make(Validator::class);
    }
}
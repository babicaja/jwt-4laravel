<?php

namespace Tests\JWT4L;

use JWT4L\Checks\Structure;
use JWT4L\Exceptions\JWTAuthorizationHeaderMissingException;
use JWT4L\Exceptions\JWTNotValidException;
use JWT4L\Generator;
use JWT4L\Parser;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;

class ParserTest extends PackageTest
{
    /**
     * @var string
     */
    private $validToken;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['jwt.algorithm'  => 'sha256']);
        config(['jwt.secret'  => 'parser-secret']);
        config(['jwt.expires'  => 15]);
        config(['jwt.checks' => [
            Structure::class,
        ]]);

        $this->validToken = $this->app->make(Generator::class)->create();
        $this->parser = $this->app->make(Parser::class);
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_only_run_the_configured_checks_on_validation()
    {
        $this->assertInstanceOf(Parser::class, $this->parser->validate("one.two.three"));
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_a_proper_exception_if_validation_fails()
    {
        $this->expectException(JWTNotValidException::class);
        $this->parser->validate("one.two");
    }

    /**
     * @test
     * @throws mixed
     */
    public function it_can_extract_the_payload_from_a_valid_token()
    {
        $this->assertInstanceOf(Payload::class, $this->parser->payload($this->validToken));
    }

    /**
     * @test
     * @throws mixed
     */
    public function it_can_extract_the_header_from_a_valid_token()
    {
        $this->assertInstanceOf(Header::class, $this->parser->header($this->validToken));
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_use_the_bearer_token_if_the_token_is_not_provided_on_validation()
    {
        request()->headers->add(["Authorization" => "Bearer {$this->validToken}"]);
        $this->assertInstanceOf(Parser::class, $this->parser->validate());
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_a_proper_exception_if_the_token_is_not_in_the_headers()
    {
        $this->expectException(JWTAuthorizationHeaderMissingException::class);
        $this->parser->validate();
    }
}
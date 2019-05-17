<?php

namespace Tests\Unit\JWT;

use App\Services\JWT\Checks\Structure;
use App\Services\JWT\Generator;
use App\Services\JWT\Parser;
use App\Services\JWT\Sections\Header;
use App\Services\JWT\Sections\Payload;
use Tests\TestCase;

class ParserTest extends TestCase
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
     * @expectedException \App\Services\JWT\Exceptions\JWTNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_a_proper_exception_if_validation_fails()
    {
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
     * @expectedException \App\Services\JWT\Exceptions\JWTAuthorizationHeaderMissingException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_a_proper_exception_if_the_token_is_not_in_the_headers()
    {
        $this->parser->validate();
    }
}

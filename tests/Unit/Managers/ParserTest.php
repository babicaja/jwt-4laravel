<?php

namespace Tests\JWT4L\Unit\Managers;

use Illuminate\Contracts\Container\BindingResolutionException;
use JWT4L\Checks\Structure;
use JWT4L\Exceptions\JWTAuthorizationHeaderMissing;
use JWT4L\Exceptions\JWTHeaderNotValid;
use JWT4L\Exceptions\JWTPayloadNotValid;
use JWT4L\Managers\Generator;
use JWT4L\Managers\Parser;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use Tests\JWT4L\BaseTest;

class ParserTest extends BaseTest
{
    /**
     * @var string
     */
    private $validToken;

    /**
     * @var Parser
     */
    private $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->overrideConfiguration(['jwt.checks' => [Structure::class]]);

        $this->validToken = $this->app->make(Generator::class)->create();
        $this->parser = $this->app->make(Parser::class);
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
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTHeaderNotValid
     * @throws JWTPayloadNotValid
     */
    public function it_will_use_the_bearer_token_if_the_token_is_not_provided()
    {
        request()->headers->add(["Authorization" => "Bearer {$this->validToken}"]);
        $this->assertInstanceOf(Payload::class, $this->parser->payload());
    }

    /**
     * @test
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTHeaderNotValid
     * @throws JWTPayloadNotValid
     */
    public function it_will_throw_a_proper_exception_if_the_token_is_not_provided_or_not_in_the_header()
    {
        $this->expectException(JWTAuthorizationHeaderMissing::class);
        $this->parser->payload();
    }
}

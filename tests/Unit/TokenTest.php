<?php

namespace Tests\JWT4L\Unit;

use JWT4L\Exceptions\JWTMethodNotSupported;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use JWT4L\Token;
use Tests\JWT4L\BaseTest;

class TokenTest extends BaseTest
{
    /**
     * @var Token
     */
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = $this->app->make(Token::class);
    }

    /** @test **/
    public function it_will_magically_call_generator_methods()
    {
        $this->assertIsString($this->token->create());
        $this->assertIsString($this->token->withPayload(['test'=>'payload'])->create());
        $this->assertIsString($this->token->withHeader(['test'=>'header'])->create());
    }

    /** @test **/
    public function it_will_magically_call_parser_methods()
    {
        $token = $this->token->create();

        $this->assertInstanceOf(Payload::class, $this->token->payload($token));
        $this->assertInstanceOf(Header::class, $this->token->header($token));
    }

    /** @test **/
    public function it_will_magically_call_validator_methods()
    {
        $token = $this->token->create();

        $this->assertTrue($this->token->validate($token));
    }

    /** @test */
    public function it_should_throw_an_exception_on_invalid_method_call()
    {
        $this->expectException(JWTMethodNotSupported::class);
        $this->token->invalid();
    }

    /** @test **/
    public function it_can_recover_from_failed_magic_calls()
    {
        $this->expectException(JWTMethodNotSupported::class);
        $this->token->invalid();
        $this->assertIsString($this->token->create());
    }
}
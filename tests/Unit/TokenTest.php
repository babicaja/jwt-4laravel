<?php

namespace Tests\JWT4L\Unit;

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
    }

    /** @test **/
    public function it_will_magically_call_parser_methods()
    {
        $token = $this->token->create();
        $this->assertInstanceOf(Payload::class, $this->token->payload($token));
    }

    /** @test */
    public function it_should_throw_an_exception_on_invalid_method_call()
    {
        $this->expectExceptionMessage("The invalid method is not supported");
        $this->token->invalid();
    }
}
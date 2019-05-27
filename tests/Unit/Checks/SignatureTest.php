<?php

namespace Tests\JWT4L\Unit\Checks;

use JWT4L\Checks\Signature;
use JWT4L\Exceptions\JWTHeaderNotValidException;
use JWT4L\Exceptions\JWTPayloadNotValidException;
use JWT4L\Exceptions\JWTSignatureNotValidException;
use JWT4L\Generator;
use JWT4L\Traits\Encoder;
use Tests\JWT4L\BaseTest;

class SignatureTest extends BaseTest
{
    use Encoder;

    /**
     * @var Signature
     */
    private $check;

    /**
     * @var string
     */
    private $validToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->check = $this->app->make(Signature::class);
        $this->validToken = $this->app->make(Generator::class)->create();
    }

    /** @test */
    public function it_will_throw_a_proper_exception_if_the_token_signatures_are_not_equal()
    {
        $this->overrideConfiguration(['jwt.secret'  => 'not-signature-secret']);

        $token = $this->app->make(Generator::class)->create();

        $this->expectException(JWTSignatureNotValidException::class);

        $this->check->validate($token);
    }

    /** @test */
    public function it_will_finish_silently_if_the_token_signatures_are_equal()
    {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->assertNull($this->check->validate($this->validToken));
    }

    /** @test */
    public function it_will_throw_a_proper_exception_if_the_header_claims_were_manipulated()
    {
        $manipulatedHeader = $this->encode(['typ' => 'manipulated', 'alg' => 'very-bad']);
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, $manipulatedHeader, 0);

        $this->expectException(JWTSignatureNotValidException::class);

        $this->check->validate($manipulatedToken);
    }

    /** @test */
    public function it_will_throw_a_proper_exception_if_the_payload_claims_were_manipulated()
    {
        $manipulatedPayload = $this->encode(['exp' => "2012-12-21"]);
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, $manipulatedPayload, 1);

        $this->expectException(JWTSignatureNotValidException::class);

        $this->check->validate($manipulatedToken);
    }

    /** @test */
    public function it_will_throw_a_proper_exception_if_the_header_claims_are_invalid()
    {
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, "bad-header", 0);

        $this->expectException(JWTHeaderNotValidException::class);

        $this->check->validate($manipulatedToken);
    }

    /** @test */
    public function it_will_throw_a_proper_exception_if_the_payload_claims_are_invalid()
    {
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, "bad-payload", 1);

        $this->expectException(JWTPayloadNotValidException::class);

        $this->check->validate($manipulatedToken);
    }

    /**
     * Replace a section of a token with provided string.
     *
     * @param string $validToken
     * @param string $manipulatedSection
     * @param int $sectionPosition
     * @return string
     */
    private function replaceSectionInToken(string $validToken, string $manipulatedSection, int $sectionPosition)
    {
        $sections = explode('.', $validToken);
        $sections[$sectionPosition] = $manipulatedSection;

        return implode('.', $sections);
    }
}

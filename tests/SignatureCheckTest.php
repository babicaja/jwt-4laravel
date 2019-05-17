<?php

namespace Tests\Unit\JWT;

use App\Services\JWT\Checks\Signature;
use App\Services\JWT\Generator;
use App\Services\JWT\Traits\Encoder;
use Tests\TestCase;

class SignatureCheckTest extends TestCase
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

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['jwt.algorithm'  => 'sha256']);
        config(['jwt.secret'  => 'signature-secret']);
        config(['jwt.expires'  => 15]);

        $this->check = $this->app->make(Signature::class);
        $this->validToken = $this->app->make(Generator::class)->create();
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @expectedException \App\Services\JWT\Exceptions\JWTSignatureNotValidException
     */
    public function it_will_throw_a_proper_exception_if_the_token_signatures_are_not_equal()
    {
        config(['jwt.algorithm'  => 'sha256']);
        config(['jwt.secret'  => 'not-signature-secret']);
        config(['jwt.expires'  => 15]);

        $token = $this->app->make(Generator::class)->create();
        $this->check->validate($token);
    }

    /** @test */
    public function it_will_finish_silently_if_the_token_signatures_are_equal()
    {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->assertNull($this->check->validate($this->validToken));
    }

    /**
     * @test
     * @expectedException \App\Services\JWT\Exceptions\JWTSignatureNotValidException
     */
    public function it_will_throw_a_proper_exception_if_the_header_claims_were_manipulated()
    {
        $manipulatedHeader = $this->encode(['typ' => 'manipulated', 'alg' => 'very-bad']);
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, $manipulatedHeader, 0);

        $this->check->validate($manipulatedToken);
    }

    /**
     * @test
     * @expectedException \App\Services\JWT\Exceptions\JWTSignatureNotValidException
     */
    public function it_will_throw_a_proper_exception_if_the_payload_claims_were_manipulated()
    {
        $manipulatedPayload = $this->encode(['exp' => "2012-12-21"]);
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, $manipulatedPayload, 1);

        $this->check->validate($manipulatedToken);
    }

    /**
     * @test
     * @expectedException \App\Services\JWT\Exceptions\JWTHeaderNotValidException
     */
    public function it_will_throw_a_proper_exception_if_the_header_claims_are_invalid()
    {
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, "bad-header", 0);

        $this->check->validate($manipulatedToken);
    }

    /**
     * @test
     * @expectedException \App\Services\JWT\Exceptions\JWTPayloadNotValidException
     */
    public function it_will_throw_a_proper_exception_if_the_payload_claims_are_invalid()
    {
        $manipulatedToken = $this->replaceSectionInToken($this->validToken, "bad-payload", 1);

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

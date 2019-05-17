<?php

namespace Tests\JWT4L;

use JWT4L\Exceptions\JWTAlgorithmNotSupportedException;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use JWT4L\Sections\Signature;

class SignatureTest extends PackageTest
{
    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_if_the_algorithm_is_unsupported()
    {
        $this->expectException(JWTAlgorithmNotSupportedException::class);
        $this->makeSignatureWithAlgorithm('test');
    }

    /**
     * @test
     * @param string $algorithm
     * @dataProvider supportedAlgorithms
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_can_sign_with_all_supported_algorithms(string $algorithm)
    {
        /** @var Signature $signature */
        $signature = $this->makeSignatureWithAlgorithm($algorithm);

        /** @var Header $header */
        $header = app()->make(Header::class);
        /** @var Payload $payload */
        $payload = app()->make(Payload::class);

        $hashedSignature = $signature->sign($header, $payload);
        $manualSignature = hash_hmac($algorithm, $header->make(). '.' . $payload->make(), config('jwt.secret'));

        $this->assertTrue(hash_equals($manualSignature, $hashedSignature));
    }

    /**
     * All the supported hash algorithms.
     *
     * @return array
     */
    public function supportedAlgorithms()
    {
        return array_map(function($value){
            return [$value];
        }, hash_hmac_algos());
    }

    /**
     * Resolve Signature with provided algorithm.
     *
     * @param string $algorithm
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function makeSignatureWithAlgorithm(string $algorithm)
    {
        // set the algorithm type in the config
        config(['jwt.algorithm' => $algorithm]);
        config(['jwt.secret' => 'test-secret']);

        return $this->app->make(Signature::class);
    }
}

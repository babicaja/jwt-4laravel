<?php

namespace App\Services\JWT\Traits;

use App\Services\JWT\Exceptions\JWTHeaderNotValidException;
use App\Services\JWT\Exceptions\JWTNotValidException;
use App\Services\JWT\Exceptions\JWTPayloadNotValidException;
use App\Services\JWT\Sections\Header;
use App\Services\JWT\Sections\Payload;

trait Detokenize
{
    /**
     * Creates a Header object from the provided token.
     *
     * @param string $token
     * @return mixed
     * @throws JWTHeaderNotValidException
     * @throws JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function headerFromToken(string $token)
    {
        $claims = $this->extractClaims($token, 0, JWTHeaderNotValidException::class);

        return app()->make(Header::class)->with($claims, true);
    }

    /**
     * Creates a Payload object from the provided token.
     *
     * @param string $token
     * @return mixed
     * @throws JWTHeaderNotValidException
     * @throws JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function payloadFromToken(string $token)
    {
        $claims = $this->extractClaims($token, 1, JWTPayloadNotValidException::class);

        return app()->make(Payload::class)->with($claims, true);
    }

    /**
     * Extracts the signature portion from the token.
     *
     * @param string $token
     * @return string
     * @throws JWTNotValidException
     */
    protected function signatureFromToken(string $token)
    {
       return $this->rawSection($token, 2);
    }


    /**
     * @param string $token
     * @param int $section
     * @param string $exceptionClass
     * @return array
     * @throws JWTHeaderNotValidException|JWTPayloadNotValidException
     */
    private function extractClaims(string $token, int $section, string $exceptionClass)
    {
        try
        {
            return (array)($this->decodeFromRaw($this->rawSection($token, $section)));
        }
        catch (JWTNotValidException $exception)
        {
            /** @var JWTHeaderNotValidException|JWTPayloadNotValidException $exceptionClass */
            throw new $exceptionClass($exception);
        }
    }

    /**
     * @param string $token
     * @param int $section
     * @return string
     * @throws JWTNotValidException
     */
    private function rawSection(string $token, int $section)
    {
        $sections = explode('.', $token);

        if (!isset($sections[$section])) throw new JWTNotValidException();

        return $sections[$section];
    }

    /**
     * @param string $raw
     * @return mixed
     * @throws JWTNotValidException
     */
    private function decodeFromRaw(string $raw)
    {
        $std = json_decode(base64_decode($raw));

        if (!$std instanceof \stdClass) throw new JWTNotValidException();

        return $std;
    }
}
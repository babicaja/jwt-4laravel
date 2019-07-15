<?php

namespace JWT4L\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use JWT4L\Exceptions\JWTHeaderNotValid;
use JWT4L\Exceptions\JWTNotValid;
use JWT4L\Exceptions\JWTPayloadNotValid;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use stdClass;

trait Detokenize
{
    /**
     * Creates a Header object from the provided token.
     *
     * @param string $token
     * @return mixed
     * @throws JWTHeaderNotValid
     * @throws JWTPayloadNotValid
     * @throws BindingResolutionException
     */
    protected function headerFromToken(string $token)
    {
        $claims = $this->extractClaims($token, 0, JWTHeaderNotValid::class);

        return app()->make(Header::class)->with($claims, true);
    }

    /**
     * Creates a Payload object from the provided token.
     *
     * @param string $token
     * @return mixed
     * @throws JWTHeaderNotValid
     * @throws JWTPayloadNotValid
     * @throws BindingResolutionException
     */
    protected function payloadFromToken(string $token)
    {
        $claims = $this->extractClaims($token, 1, JWTPayloadNotValid::class);

        return app()->make(Payload::class)->with($claims, true);
    }

    /**
     * Extracts the signature portion from the token.
     *
     * @param string $token
     * @return string
     * @throws JWTNotValid
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
     * @throws JWTHeaderNotValid|JWTPayloadNotValid
     */
    private function extractClaims(string $token, int $section, string $exceptionClass)
    {
        try
        {
            return (array)($this->decodeFromRaw($this->rawSection($token, $section)));
        }
        catch (JWTNotValid $exception)
        {
            /** @var JWTHeaderNotValid|JWTPayloadNotValid $exceptionClass */
            throw new $exceptionClass($exception);
        }
    }

    /**
     * @param string $token
     * @param int $section
     * @return string
     * @throws JWTNotValid
     */
    private function rawSection(string $token, int $section)
    {
        $sections = explode('.', $token);

        if (!isset($sections[$section])) throw new JWTNotValid();

        return $sections[$section];
    }

    /**
     * @param string $raw
     * @return mixed
     * @throws JWTNotValid
     */
    private function decodeFromRaw(string $raw)
    {
        $decoded = json_decode(base64_decode($raw));

        if (!$decoded instanceof stdClass && !is_array($decoded)) throw new JWTNotValid();

        return $decoded;
    }
}
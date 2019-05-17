<?php

namespace App\Services\JWT\Traits;

trait Encoder
{
    /**
     * Encodes array in JWT fashion. The array is first json_encoded then base64_encoded.
     *
     * @param array $claims
     * @return string
     */
    public function encode(array $claims = [])
    {
        return base64_encode(json_encode($claims));
    }

    /**
     * Decodes a string. The string is base64_decoded and then json_decoded.
     *
     * @param string $string
     * @return mixed
     */
    public function decode(string $string)
    {
        return json_decode(base64_decode($string));
    }

    /**
     * Hash the data with specified algorithm and secret.
     *
     * @param string $algorithm
     * @param string $data
     * @param string $secret
     *
     * @return string
     */
    public function hash(string $algorithm, string $data, string $secret)
    {
        return hash_hmac($algorithm, $data, $secret);
    }
}
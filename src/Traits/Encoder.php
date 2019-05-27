<?php

namespace JWT4L\Traits;

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
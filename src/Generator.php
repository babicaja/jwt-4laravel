<?php

namespace App\Services\JWT;

use App\Services\JWT\Sections\Header;
use App\Services\JWT\Sections\Payload;
use App\Services\JWT\Sections\Signature;

class Generator
{
    /**
     * @var Header
     */
    private $header;

    /**
     * @var Payload
     */
    private $payload;

    /**
     * @var Signature
     */
    private $signature;

    /**
     * Generator constructor.
     *
     * @param Header $header
     * @param Payload $payload
     * @param Signature $signature
     */
    public function __construct(Header $header, Payload $payload, Signature $signature)
    {
        $this->header = $header;
        $this->payload = $payload;
        $this->signature = $signature;
    }

    /**
     * Append or replace the default header claims.
     *
     * @param array $claims
     * @param bool $replace
     * @return $this
     */
    public function withHeader(array $claims, bool $replace = false)
    {
        $this->header->with($claims, $replace);

        return $this;
    }

    /**
     * Append or replace the default payload claims.
     *
     * @param array $claims
     * @param bool $replace
     * @return $this
     */
    public function withPayload(array $claims, bool $replace = false)
    {
        $this->payload->with($claims, $replace);

        return $this;
    }

    /**
     * Create the token.
     *
     * @return string
     */
    public function create()
    {
        $encodedHeader = $this->header->make();
        $encodedPayload = $this->payload->make();
        $hashedSignature = $this->signature->sign($this->header, $this->payload);

        return implode('.', [$encodedHeader, $encodedPayload, $hashedSignature]);
    }
}
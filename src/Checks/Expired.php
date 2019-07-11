<?php

namespace JWT4L\Checks;

use JWT4L\Exceptions\JWTExpired;
use JWT4L\Traits\Detokenize;
use Illuminate\Support\Carbon;

class Expired implements CheckContract
{
    use Detokenize;

    /**
     * Do necessary checks and throw a specific exception if conditions are not met.
     *
     * @param string $token
     * @return void
     * @throws mixed
     */
    public function validate(string $token)
    {
        $payload = $this->payloadFromToken($token);

        if (!Carbon::now()->isBefore(Carbon::create($payload->exp))) throw new JWTExpired();
    }
}
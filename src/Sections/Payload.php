<?php

namespace JWT4L\Sections;

use Carbon\Carbon;

class Payload extends Section
{
    /**
     * Sets the default payload claims.
     *
     * @param int $minutes expiration in minutes after creation.
     */
    public function __construct(int $minutes)
    {
        $this->claims = [
            "iat" => Carbon::now(),
            "exp" => Carbon::now()->addMinutes($minutes),
        ];
    }
}
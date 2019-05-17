<?php

namespace Tests\Unit\JWT;

use App\Services\JWT\Checks\Structure;
use Tests\TestCase;

class StructureCheckTest extends TestCase
{
    /**
     * @test
     * @expectedException \App\Services\JWT\Exceptions\JWTNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_a_proper_exception_if_a_three_part_token_is_not_provided()
    {
        $check = $this->app->make(Structure::class);

        $check->validate('one.two');
    }

    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_finish_silently_if_a_three_part_token_is_provided()
    {
        $check = $this->app->make(Structure::class);

        $this->assertNull($check->validate('one.two.three'));
    }
}
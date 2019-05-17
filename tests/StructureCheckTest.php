<?php

namespace Tests\JWT4L;

use JWT4L\Checks\Structure;
use JWT4L\Exceptions\JWTNotValidException;

class StructureCheckTest extends PackageTest
{
    /**
     * @test
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function it_will_throw_a_proper_exception_if_a_three_part_token_is_not_provided()
    {
        $check = $this->app->make(Structure::class);

        $this->expectException(JWTNotValidException::class);

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
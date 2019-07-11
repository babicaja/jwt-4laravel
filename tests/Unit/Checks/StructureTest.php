<?php

namespace Tests\JWT4L\Unit\Checks;

use JWT4L\Checks\Structure;
use JWT4L\Exceptions\JWTNotValid;
use Tests\JWT4L\BaseTest;

class StructureTest extends BaseTest
{
    /** @test */
    public function it_will_throw_a_proper_exception_if_a_three_part_token_is_not_provided()
    {
        $check = $this->app->make(Structure::class);

        $this->expectException(JWTNotValid::class);

        $check->validate('one.two');
    }

    /** @test */
    public function it_will_finish_silently_if_a_three_part_token_is_provided()
    {
        $check = $this->app->make(Structure::class);

        $this->assertNull($check->validate('one.two.three'));
    }
}
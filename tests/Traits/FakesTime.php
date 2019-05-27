<?php

namespace Tests\JWT4L\Traits;

use Illuminate\Support\Carbon;

trait FakesTime
{
    /**
     * @var Carbon
     */
    public $testNow;

    /**
     * Set the time to a fix time for testing purpose.
     */
    public function setTime()
    {
        $this->testNow = Carbon::create(2012, 12, 21, 12, 0, 0);

        Carbon::setTestNow($this->testNow);
    }

    /**
     * Move the test time forth in minutes.
     *
     * @param int $minutes
     */
    public function moveTime(int $minutes)
    {
        $this->setTime();

        Carbon::setTestNow($this->testNow->addMinutes($minutes));
    }
}
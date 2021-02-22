<?php

namespace BeyondCode\QueryDetector\Concerns;

trait Bootable {
    /**
     * Indicates if query detector was "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * Determine if query detector was booted
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }
    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    public function bootIfNotBooted() : void
    {
        if ($this->isBooted()) {
            return;
        }

        $this->boot();
    }

    protected function boot() : void
    {
    }
}
<?php

namespace BeyondCode\QueryDetector\Concerns;

use Illuminate\Support\Str;

trait HasContext {
    /** @var string */
    protected $context = '';

    /**
     * Set specific context for the executed queries.
     *
     * @param string $context
     * @return self
     */
    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Generate a new context for the executed queries.
     *
     * @return self
     */
    public function newContext(): self
    {
        $this->context = Str::random();

        return $this;
    }
}
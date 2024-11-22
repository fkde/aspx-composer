<?php

namespace Aspx;

trait Factory
{

    /**
     * @param mixed ...$args
     *
     * @return static
     */
    public static function factory(...$args): static
    {
        return new static(...$args);
    }

}
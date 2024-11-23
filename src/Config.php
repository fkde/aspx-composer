<?php

namespace Aspx;

/**
 * Immutable configuration
 */
class Config
{

    protected array $items;

    /**
     * @param array|null $items
     */
    public function __construct(?array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param string      $key
     * @param string|null $default
     *
     * @return mixed
     */
    public function get(string $key, ?string $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

}
<?php

namespace App\Services\Traits;

trait Create
{
    /**
     * Create an instance of the current class.
     *
     * @return Self
     */
    public static function create(...$args): Self
    {
        return new Self(...$args);
    }
}

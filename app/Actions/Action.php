<?php

namespace App\Actions;

abstract class Action
{
    public static function run(...$arguments)
    {
        return app(static::class)->handle(...$arguments);
    }
}

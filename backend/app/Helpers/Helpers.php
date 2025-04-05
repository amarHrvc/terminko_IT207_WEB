<?php

namespace App\Helpers;

class Helpers
{

    /**
     * @param mixed $result
     * @return void
     */
    public static function testOutput(mixed $result): void
    {
        fwrite(STDERR, print_r($result, true));
    }
}
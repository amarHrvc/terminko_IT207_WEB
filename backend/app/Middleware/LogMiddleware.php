<?php

namespace App\Middleware;

use Flight;

class LogMiddleware
{
    public function before($params)
    {
        echo 'Middleware first!';
    }

    public function after($params)
    {
        echo 'Middleware last!';
    }
}

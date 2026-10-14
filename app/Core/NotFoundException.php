<?php

namespace App\Core;

/** Thrown by the router when no route matches. */
class NotFoundException extends \Exception
{
    protected $message = 'Not Found';
    protected $code    = 404;
}

<?php

namespace App\Core;

/** Generic HTTP exception carrying a status code. */
class HttpException extends \Exception
{
    protected int $status;

    public function __construct(string $message = 'Error', int $status = 500)
    {
        parent::__construct($message);
        $this->status = $status;
    }

    public function getStatus(): int { return $this->status; }
}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class AppException extends Exception
{
    protected $statusCode;

    public function __construct(string $message = "", int $code = 0, int $statusCode = 500)
    {
        parent::__construct($message, $code);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
<?php


namespace App\Exceptions;


use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class CustomException extends Exception
{
    public $responseCode;

    public function __construct($message = "", $code = 0, Throwable $previous = null, $responseCode = 400)
    {
        $this->responseCode = $responseCode;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param int $responseCode
     * @return static
     */
    public static function create($message = "", $code = 0, Throwable $previous = null, $responseCode = 400): static
    {
        return new static($message, $code, $previous, $responseCode = 400);
    }

}
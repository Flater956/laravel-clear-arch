<?php


namespace App\Exceptions\Auth;


use App\Exceptions\CustomException;
use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class AuthValidationException extends CustomException
{
    /**
     * AuthValidationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    #[Pure]
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}

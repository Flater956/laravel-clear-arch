<?php


namespace App\Exceptions\Repositories\User;


use App\Exceptions\CustomException;
use JetBrains\PhpStorm\Pure;
use Throwable;

class UserRepositoryException extends CustomException
{
    /**
     * UserRepositoryException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}

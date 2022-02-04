<?php


namespace App\Repositories;


use App\Exceptions\CustomException;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    /**
     * @var string|null
     */
    protected ?string $customExceptionClassName;
    protected Model $model;

    /**
     * Repository constructor.
     * @param Model $model
     * @param string|null $customExceptionClassName
     */
    public function __construct(?string $customExceptionClassName = null)
    {
        $this->customExceptionClassName = $customExceptionClassName;
    }

    /**
     * @param array $errorParams
     * @param int $responseCode
     * @throws \App\Exceptions\CustomException
     */
    protected function triggerException(array $errorParams, $responseCode = 400)
    {
        if ($this->customExceptionClassName) {
            /** @var CustomException $exception */
            $exception = $this->customExceptionClassName::create(
                $errorParams['errorMessage'],
                $errorParams['errorCode'],
                null,
                $responseCode
            );

            throw $exception;
        }
    }
}

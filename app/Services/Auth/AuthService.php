<?php

namespace App\Services\Auth;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Definition\ErrorCodes;
use App\Exceptions\Auth\AuthValidationException;
use App\Exceptions\CustomException;
use App\Mail\ChangePasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\ArrayShape;

class AuthService
{
    private UserRepositoryInterface $userRepository;
    private ?User $currentUser = null;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @throws CustomException
     */
    public function signUp(Request $request)
    {
        $this->userRepository->createUser(
            $request->input('phone'),
            $request->input('password'),
            $request->input('email'),
            $request->input('first_name'),
            $request->input('role'),
            $request->input('second_name')
        );
    }


    /**
     * @param Request $request
     * @return array
     * @throws AuthValidationException
     */
    #[ArrayShape(['token' => "string", 'userId' => "int"])]
    public function getAccessTokenByLoginData(Request $request): array
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            self::triggerException(ErrorCodes::NOT_VALID_PASSWORD_ERROR);
        }

        $userId = $this->getCurrentUserId();
        return ['token' => $token, 'userId' => $userId];
    }

    private function getCurrentUserId(): int
    {
        if (!$this->currentUser) {
            return $this->setCurrentUser()->id;
        }

        return $this->currentUser->id;
    }

    /**
     * @param array $errorParams
     * @throws AuthValidationException
     */
    private static function triggerException(array $errorParams)
    {
        throw new AuthValidationException($errorParams['errorMessage'], $errorParams['errorCode']);
    }

    /**
     * @return User|null
     */
    private function setCurrentUser(): ?User
    {
        $this->currentUser = $this->userRepository::getCurrentUser();

        return $this->currentUser;
    }

}

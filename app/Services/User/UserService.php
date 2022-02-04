<?php


namespace App\Services\User;

use App\Exceptions\CustomException;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\Email\EmailException;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\Email\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use JetBrains\PhpStorm\ArrayShape;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws CustomException
     */
    #[ArrayShape([
        'email' => "string",
        'first_name' => "string",
        'id' => "int",
        'phone' => "string",
        'role' => "string",
        'second_name' => "string"
    ])]
    public function getUserById(int $userId): array
    {
        /** @var \App\Models\User $user */
        $user = $this->userRepository->getById($userId);
        return $user->toArray();
    }

    /**
     * @return array
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers()->toArray();
    }

    /**
     * @param Request $request
     */
    public function updateCurrentUserFcmToken(Request $request): void
    {
        $fcmToken = $request->input('token');
        $this->userRepository->updateCurrentUserFCMToken($fcmToken);
    }

    public function updateUser(Request $request, int $userId)
    {
        $newData = [
            'email' => $request->input('email'),
            'first_name' => $request->input('first_name'),
            'job_title' => $request->input('job_title'),
            'phone' => $request->input('phone'),
            'second_name' => $request->input('second_name')
        ];

        $this->userRepository->updateUserById($userId, $newData);
    }

    /**
     * @return User|null
     */
    public static function getCurrentUser(): ?User
    {
        return UserRepository::getCurrentUser();
    }

    /**
     * @param array|int $usersIds
     * @return array
     */
    public static function getUsersTokens(array|int $usersIds): array
    {
        $tokens = [];
        $tokensInfo = UserRepository::getUsersFcmTokens($usersIds);

        foreach ($tokensInfo as $tokenInfo) {
            $tokens[] = $tokenInfo->token;
        }
        return $tokens;
    }

    /**
     * @param string $role
     * @return array
     */
    public static function getUsersIdsByRole(string $role): array
    {
        $usersIds = [];

        $users = UserRepository::getUsersIdsByRole($role);

        /** @var User $user */
        foreach ($users as $user) {
            $usersIds[] = $user->id;
        }

        return $usersIds;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \App\Exceptions\Email\EmailException
     */
    public function sendConfirmCode(Request $request)
    {
        $email  = $request->input('email');
        $user = $this->userRepository->getUserByEmail($email);
        $code = rand(100000, 999999);
        self::saveConfirmCodeToRedis($email, $code);

        $emailService = new EmailService();
        $emailService->sendConfirmCodeEmail(
            $user->first_name,
            $user->second_name,
            $code,
            $email
        );
    }

    /**
     * @param string $email
     * @param string $code
     * @throws \App\Exceptions\Email\EmailException
     */
    private static function saveConfirmCodeToRedis(string $email, int $code): void
    {
        if (!Redis::set('email:'.$email, $code, 'EX', 480)) {
            throw new EmailException('Ошибка отправки email с кодом подтверждения', 15);
        }
    }

    private static function getConfirmCodeFromServer(string $email): ?int
    {
        return Redis::get('email:'.$email);
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @throws \App\Exceptions\CustomException
     */
    public function changePassword(Request $request)
    {
        $email = $request->input('email');
        $userConfirmCode = $request->input('confirm_code');
        $newPassword = $request->input('new_password');
        $confirmCodeFromServer = self::getConfirmCodeFromServer($email);

        if ((int)$userConfirmCode !== (int)$confirmCodeFromServer) {
            throw new CustomException('Не верный код подтверждения', 20);
        }

        $this->userRepository->changePassword($email, $newPassword);
    }

}

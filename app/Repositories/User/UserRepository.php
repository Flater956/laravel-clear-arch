<?php


namespace App\Repositories\User;


use App\Contracts\Repositories\UserRepositoryInterface;
use App\Definition\ErrorCodes;
use App\Exceptions\CustomException;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;


class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    private $userModel;


    /**
     * UserRepository constructor.
     * @param \App\Models\User $userModel
     * @param string|null $customExceptionClassName
     */
    #[Pure]
    public function __construct(User $userModel, ?string $customExceptionClassName = null)
    {
        parent::__construct($customExceptionClassName);
        $this->userModel = $userModel;
    }

    /**
     * @param string|null $login
     * @return User
     * @throws CustomException
     */
    public function findOrFailUserByLogin(?string $login): User
    {
        /** @var User $user */
        $user = $this->userModel->where('login', $login)->first();

        if (!$user) {
            $this->triggerException(ErrorCodes::NOT_FOUND_USER_BY_LOGIN_ERROR);
        }

        return $user;
    }

    /**
     * @param string $phone
     * @param string $password
     * @param string $email
     * @param string $firstName
     * @param string $role
     * @param string $secondName
     * @throws \App\Exceptions\CustomException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createUser(
        string $phone,
        string $password,
        string $email,
        string $firstName,
        string $role,
        string $secondName
    )
    {
        $newUser = $this->userModel->create([
            'phone' => $phone,
            'password' => app('hash')->make($password),
            'email' => $email,
            'first_name' => $firstName,
            'second_name' => $secondName
        ]);

        if (!$newUser) {
            $this->triggerException(ErrorCodes::CAN_NOT_CREATE_USER_ERROR);
        }

        $newUser->assignRole($role);
    }

    /**
     * @return User|null
     */
    public static function getCurrentUser(): ?User
    {
        return auth()->user();
    }

    /**
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return $this->userModel->all();
    }

    /**
     * @param string $fcmToken
     */
    public function updateCurrentUserFCMToken(string $fcmToken): void
    {
        $currentUser = self::getCurrentUser();
        $currentUser->token = $fcmToken;
        $currentUser->save();
    }

    /**
     * @param int $userId
     * @param array $newData
     * @throws CustomException
     */
    public function updateUserById(int $userId, array $newData): void
    {
        $user = $this->getById($userId);

        if (!$user) $this->triggerException(ErrorCodes::NOT_FOUND_USER_BY_ID_ERROR);

        $user->update($newData);
    }

    /**
     * @param int|array $usersIds
     * @return \Illuminate\Support\Collection
     */
    public static function getUsersFcmTokens(int|array $usersIds): Collection
    {
        return User::select('token')->whereIn('id', $usersIds)->get();
    }

    /**
     * @param string $role
     * @return \Illuminate\Support\Collection
     */
    public static function getUsersIdsByRole(string $role): Collection
    {
        return User::select('id')->where('role', $role)->get();
    }

    /**
     * @param string $email
     * @return \App\Models\User
     * @throws \App\Exceptions\CustomException
     */
    public function getUserByEmail(string $email): User
    {
        /** @var User $user */
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            $this->triggerException(ErrorCodes::NOT_FOUND_USER_BY_EMAIL_ERROR);
        }

        return  $user;
    }

    /**
     * @param string $email
     * @param string $newPassword
     * @throws \App\Exceptions\CustomException|\Illuminate\Contracts\Container\BindingResolutionException
     */
    public function changePassword(string $email, string $newPassword)
    {
        $user = $this->getUserByEmail($email);
        $user->password = app('hash')->make($newPassword);
        $user->save();
    }

    public function getById(int $id)
    {
        return $this->userModel->where('id', $id)->first();
    }
}

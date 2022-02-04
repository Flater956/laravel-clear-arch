<?php


namespace App\Contracts\Repositories;


use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{

    /**
     * @param int $id
     * @throws CustomException
     */
    public function getById(int $id);

    /**
     * @param string $phone
     * @param string $password
     * @param string $email
     * @param string $firstName
     * @param string $role
     * @param string $secondName
     * @throws \App\Exceptions\CustomException
     */
    public function createUser(
        string $phone,
        string $password,
        string $email,
        string $firstName,
        string $role,
        string $secondName
    );

    /**
     * @return User|null
     */
    public static function getCurrentUser(): ?User;

    public function getAllUsers(): Collection;

    public function updateCurrentUserFCMToken(string $fcmToken): void;

    public function updateUserById(int $userId, array $newData);

    public function getUserByEmail(string $email): User;

    public function changePassword(string $email, string $newPassword);

}

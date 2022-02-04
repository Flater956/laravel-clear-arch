<?php


namespace App\Http\Controllers\User;


use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Services\User\UserValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{

    /**
     * @param int $userId
     * @param UserService $userService
     * @return JsonResponse
     * @throws CustomException
     */
    public function getUserById(int $userId, UserService $userService): JsonResponse
    {
        return $this->successResponse(200, $userService->getUserById($userId));
    }

    /**
     * @param UserService $userService
     * @return JsonResponse
     */
    public function getAllUsers(UserService $userService): JsonResponse
    {
        return response()->json($userService->getAllUsers());
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function updateUserFcmToken(Request $request, UserService $userService): JsonResponse
    {
        $userService->updateCurrentUserFcmToken($request);

        return $this->successResponse();
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @param int $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateUserById(Request $request, UserService $userService, int $userId): JsonResponse
    {
        (new UserValidationService())->validateUpdateUserRequest($request);
        $userService->updateUser($request, $userId);

        return $this->successResponse();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\User\UserService $userService
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Email\EmailException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendConfirmCode(Request $request, UserService $userService): JsonResponse
    {
        (new UserValidationService())->validateSendConfirmCodeRequest($request);
        $userService->sendConfirmCode($request);

        return $this->successResponse();

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\User\UserService $userService
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\CustomException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePassword(Request $request, UserService $userService): JsonResponse
    {
        (new UserValidationService())->validateChangePasswordRequest($request);
        $userService->changePassword($request);

        return $this->successResponse();
    }
}
<?php


namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\AuthValidationException;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use AuthHelper;

    /**
     * Store a new user.
     *
     * @param Request $request
     * @param AuthService $authService
     * @param AuthValidationService $authValidationService
     * @return JsonResponse
     * @throws CustomException
     * @throws ValidationException
     */
    public function signUp(
        Request $request,
        AuthService $authService,
        AuthValidationService $authValidationService
    ): JsonResponse
    {
        $authValidationService->validateSignUpRequest($request);
        $authService->signUp($request);

        return $this->successResponse(200, [
            'entity' => 'user',
            'message' => 'user success created'
        ]);


    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @param AuthService $authService
     * @param AuthValidationService $authValidationService
     * @return JsonResponse
     * @throws AuthValidationException
     */
    public function signIn(
        Request $request,
        AuthService $authService,
        AuthValidationService $authValidationService
    ): JsonResponse
    {
        $authValidationService->validateSignInRequest($request);
        $signInData = $authService->getAccessTokenByLoginData($request);

        return $this->respondWithTokenAndUserId($signInData['token'], $signInData['userId']);
    }

    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

<?php


namespace App\Http\Controllers\Auth;


use Illuminate\Http\JsonResponse;

trait AuthHelper
{
    /**
     * @param $token
     * @param $userId
     * @return JsonResponse
     */
    protected function respondWithTokenAndUserId($token, $userId): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'id' => $userId,
            'expires_in' => null
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param int $status
     * @param array $respBody
     * @return JsonResponse
     */
    protected function successResponse(int $status = 200, array $respBody = []): JsonResponse
    {
        $defaultBody = ['state' => 'success'];
        $body = array_merge($defaultBody, $respBody);
        return self::setResponse($body, $status);
    }

    /**
     * @param int $status
     * @param array $respBody
     * @return JsonResponse
     */
    protected function errorResponse(int $status = 400, array $respBody = []): JsonResponse
    {
        $defaultBody = ['state' => 'error'];
        $body = array_merge($defaultBody, $respBody);
        return self::setResponse($body, $status);
    }

    /**
     * @param array $body
     * @param int $status
     * @return JsonResponse
     */
    private static function setResponse(array $body, int $status): JsonResponse
    {
        return response()->json($body, $status);
    }
}

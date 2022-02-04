<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class AuthValidationService
{
    use ProvidesConvenienceMethods;

    /**
     * @throws ValidationException
     */
    public function validateSignUpRequest(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string',
            'password' => 'required|string',
            "email" => "required|string",
            "first_name" => "required|string",
            "job_title" => "required|string",
            "register_code" => "required|string",
            "second_name" => "required|string"
        ]);
    }

    public function validateSignInRequest(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string',
            "email" => "required|string",
        ]);
    }
}

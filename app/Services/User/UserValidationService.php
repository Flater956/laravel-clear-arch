<?php


namespace App\Services\User;


use Illuminate\Http\Request;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class UserValidationService
{
    use ProvidesConvenienceMethods;

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateUpdateUserRequest(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string',
            "email" => "required|string",
            "first_name" => "required|string",
            "job_title" => "required|string",
            "second_name" => "required|string"
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateSendConfirmCodeRequest(Request $request)
    {
        $this->validate($request, [
            "email" => "required|string",
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateChangePasswordRequest(Request $request)
    {
        $this->validate($request, [
            "email" => "required|string",
            'confirm_code' => 'required|string',
            'new_password' => 'required|string'
        ]);
    }
}
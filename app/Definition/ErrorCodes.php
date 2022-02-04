<?php


namespace App\Definition;


class ErrorCodes
{
    public const NOT_VALID_LOGIN_ERROR = ['errorCode' => 1, 'errorMessage' => 'Не валидный логин'];

    public const NOT_VALID_PASSWORD_ERROR = ['errorCode' => 2, 'errorMessage' => 'Не валидный пароль'];

    public const NOT_VALID_CONFIRM_CODE = ['errorCode' => 3, 'errorMessage' => 'Не верный код подтверждения'];

    public const ERROR_SAVE_CONFIRM_CODE = ['errorCode' => 4, 'errorMessage' => 'Не удалось сохранить код подтверждения'];

    public const SEND_SMS_ERROR = ['errorCode' => 5, 'errorMessage' => 'Не удалось отправить СМС сообщение'];

    public const NOT_VALID_PHONE_ERROR = ['errorCode' => 6, 'errorMessage' => 'Не валидный номер телефона'];

    public const USER_HAS_NOT_PHONE = ['errorCode' => 7, 'errorMessage' => 'У данного пользователя не привязан номер телефона'];

    public const NOT_FOUND_USER_BY_LOGIN_ERROR = ['errorCode' => 8, 'errorMessage' => 'Не найден пользователь с данным логином'];

    public const CAN_NOT_CREATE_USER_ERROR = ['errorCode' => 9, 'errorMessage' => 'Не удалось создать пользователя'];

    public const NOT_VALID_USER_ROLE = ['errorCode' => 10, 'errorMessage' => 'Не корректный код роли пользователя'];

    public const NOT_FOUND_USER_BY_ID_ERROR = ['errorCode' => 11, 'errorMessage' => 'Не найден пользователь с данным id'];

    public const NOT_FOUND_USER_BY_EMAIL_ERROR = ['errorCode' => 12, 'errorMessage' => 'Не найден пользователь с данным email'];

}

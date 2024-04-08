<?php

declare(strict_types=1);

namespace App\Shared\Response;

enum ErrorCode: string
{
    case USER_UNIQUE_KEY_EXIST = 'user_unique_key_exist';
    case REQUEST_INVALIDATE = 'register_request_invalidate';
    case PAGE_NOT_FOUND = 'page_not_found';
    case LOGIN_INCORRECT_EMAIL_OR_PASSWORD = 'login_incorrect_email_or_password';
    case UNAUTHORIZED = 'unauthorized';
    case NOT_VALIDATE_CODE_VERIFY = 'not_validate_code_verify';
    case NOT_VALIDATE_RECOVERY_PASSWORD_CODE = 'not_validate_recovery_password_code';
    case NOT_VALIDATE_REFRESH_TOKEN = 'not_validate_refresh_token';
    case NOT_VALIDATE_GOOGLE_OAUTH_CODE = 'not_validate_google_oauth_code';
}

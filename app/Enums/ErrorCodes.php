<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

final class ErrorCodes extends Enum
{

    const page_not_found = 8004;
    const invalid_request = 8500;
    const invalid_token = 8006;
    const invalid_credentials = 8007;
    const bad_request = 8000;
    const method_not_allowed = 8005;




}

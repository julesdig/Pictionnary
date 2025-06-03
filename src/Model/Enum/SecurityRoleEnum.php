<?php

namespace App\Model\Enum;

enum SecurityRoleEnum: string
{
    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';

}
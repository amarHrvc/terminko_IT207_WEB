<?php

namespace App\Models\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case OWNER = 'owner';
    case CUSTOMER = 'customer';
}
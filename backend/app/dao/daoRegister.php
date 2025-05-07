<?php
namespace App\Dao;

use App\Models\Tenant;
use Flight;

Flight::register('UserDao', UserDao::class);
Flight::register('BookingDao', BookingDao::class);
Flight::register('RatingDao', RatingDao::class);
Flight::register('ServiceDao', ServiceDao::class);
Flight::register('TenantDao', TenantDao::class);
Flight::register('BookingDao', BookingDao::class);


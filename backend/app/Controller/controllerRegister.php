<?php

namespace App\Controller;

use App\Models\Tenant;
use Flight;

Flight::register('UserController', UserController::class);
Flight::register('BookingController', BookingController::class);
Flight::register('RatingController', RatingController::class);
Flight::register('ServiceController', ServiceController::class);
Flight::register('TenantController', TenantController::class);

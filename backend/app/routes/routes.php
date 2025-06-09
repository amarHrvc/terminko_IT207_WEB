<?php
namespace App\Routes;

use App\Helpers\Helpers;
use App\Middleware\AuthMiddleware;
use App\Middleware\IsAdminMiddleware;
use App\Middleware\IsOwnerMiddleware;
use App\Middleware\LogMiddleware;
use Flight;
use Flight\Engine;
use SebastianBergmann\LinesOfCode\IllogicalValuesException;


/** @var Engine $app */

//$router = $app->router();

// Test route
//Flight::route('/', function () {
//    Flight::json([
//        'message' => 'Welcome to Terminko API',
//        'status' => 'running'
//    ]);
//});
//
////Flight::route('/test', function () {
////    echo 'test !!';
////    var_dump(Flight::RatingDao()->status());
////    die("END!");
////});
//
////Flight::route('/test', "UserController->index");
//Flight::route('/test', function (){
//    Flight::UserController()->index();
//});

//FLight::resource('/users', UserController::class);

//Flight::group('/api/v1', function () {
//    Flight::route('/users', function () {
//        // Matches /api/v1/users
//    });
//
//    Flight::route('/posts', function () {
//        // Matches /api/v1/posts
//    });
//});


//Flight::group("GET /api/v1/users", function () {
//    Flight::route("/test", function (){
//        Flight::json("OLAAAAAAAAA");
//    });
//
//});


Flight::group('/api/v1/', function () {

    Flight::route('POST /register', function () {
        $data = Flight::request()->data;
        $user = FLight::UserController()->register($data->getData());

        if ($user['error'] == true) {
            Flight::jsonResponse($user, 400, JSON_PRETTY_PRINT);
            return;
        }
        Flight::jsonResponse($user, 200, JSON_PRETTY_PRINT);

    });

    Flight::route('POST /login', function () {

        $data = Flight::UserController()->login(Flight::request()->data->getData());

        if ($data['error'] == true) {
            Flight::jsonResponse($data, 400, JSON_PRETTY_PRINT);
            return;
        }
        $user = $data['user'];

        unset($user['password']);

        Flight::jsonResponse($user, 200, JSON_PRETTY_PRINT);

    });


    //Users
    Flight::group('/users', function () {

        Flight::route('GET /', function () {
            Flight::jsonResponse(Flight::UserController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {

            $user = Flight::UserController()->show($id);
            if ($user)
                Flight::jsonResponse($user, 200, JSON_PRETTY_PRINT);
            else
                Flight::jsonResponse($user, 404, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::UserController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        })->addMiddleware([new LogMiddleware(), new IsAdminMiddleware()]);

        Flight::route('DELETE /@id', function ($id) {
            $userDeleted = Flight::UserController()->delete($id);

            if($userDeleted)
                Flight::jsonResponse(['success' => "User with id: $id deleted!"], 200, JSON_PRETTY_PRINT);
            else
                Flight::jsonResponse(['error' => "User with id: $id not found!"], 404, JSON_PRETTY_PRINT);

        })->addMiddleware([new LogMiddleware(), new IsAdminMiddleware()]);

    }, [new AuthMiddleware()]);


    //Bookings
    Flight::group('/bookings', function () {

        Flight::route('/', function () {
            Flight::jsonResponse(Flight::BookingController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {
            //TODO: get bookings per tentant  id or check if booking belongs to tenant
            Flight::jsonResponse(Flight::BookingController()->show($id), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::BookingController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        })->addMiddleware(new IsAdminMiddleware());

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::BookingController()->delete($id);
            Flight::jsonResponse($delete, 200, JSON_PRETTY_PRINT);
        })->addMiddleware(new IsAdminMiddleware());

    }, [new AuthMiddleware()]);


    //Ratings
    Flight::group('/ratings', function () {

        Flight::route('/', function () {
            Flight::jsonResponse(Flight::RatingController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {
            Flight::jsonResponse(Flight::RatingController()->show($id), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::RatingController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        })->addMiddleware(new IsAdminMiddleware());

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::RatingController()->delete($id);
            Flight::jsonResponse($delete, 200, JSON_PRETTY_PRINT);
        })->addMiddleware(new IsAdminMiddleware());

    });


    //Services
    //TODO: workout additional teanant logic
    //TODO: user lookup businness/tenant and then it needs to get services ?

    Flight::group('/services', function () {

        Flight::route('/', function () {
            Flight::jsonResponse(Flight::ServiceController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {
            $service = Flight::ServiceController()->show($id);

            //TODO: get bookings per tentant  id or check if booking belongs to tenant
            Flight::jsonResponse($service, 200, JSON_PRETTY_PRINT);
        })->addMiddleware(new IsOwnerMiddleware());

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::ServiceController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        })->addMiddleware(new IsOwnerMiddleware());

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::ServiceController()->delete($id);
            Flight::jsonResponse($delete, 200, JSON_PRETTY_PRINT);
        })->addMiddleware(new IsOwnerMiddleware());

    });


    //Tenants
    Flight::group('/tenants', function () {

        Flight::route('GET /', function () {
            Flight::jsonResponse(Flight::TenantController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {

            $tenant = Flight::TenantController()->show($id);
            Helpers::checkTenantId($tenant);
            if ($tenant) {
                $response = Helpers::getResponseObject([
                    'success' => true,
                    'message' => $tenant
                ], 200);
                Helpers::jsonResponseFromObject($response, $response['code'] ?? 200);
            } else {
                Helpers::JsonResponse(
                    false,
                    "error when getting tenant with id: $id",
                    null, 
                    200);
            }


            
        });

        //TODO: tenant creation should be moved to register process
        Flight::route('POST /', function () {
            $response = Flight::TenantController()->create(Flight::request()->data->getData());

            if (!$response){
                Helpers::jsonResponse(false, "Error while creating tenantId !!!", null, 400);
            }

            Helpers::jsonResponseFromObject($response, $response['code'] ?? 200);
        })->addMiddleware(new IsOwnerMiddleware());

        Flight::route('PUT /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value mismatch");

            $updatedTenant = Flight::TenantController()->update($data->getData());
            Helpers::jsonResponse(true, "Tenant updated successfully", $updatedTenant, 200);
        })->addMiddleware(new IsOwnerMiddleware());

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::TenantController()->delete($id);
            Helpers::jsonResponse(true, "Tenant deleted successfully", $delete, 200);
        })->addMiddleware(new IsAdminMiddleware());

    }, [new AuthMiddleware()]);


    FLight::group('/*', function (){

    }, [new AuthMiddleware()]);


});
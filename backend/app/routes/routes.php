<?php
namespace App\Routes;

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


    //Users
    Flight::group('/users', function () {

        /**
         * @OA\Get(
         *     path="/api/v1/users",
         *     @OA\Response(response="200", description="Get all users")
         * )
         */
        Flight::route('/', function () {
            Flight::jsonResponse(Flight::UserController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {
            Flight::jsonResponse(Flight::UserController()->show($id), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::UserController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('DELETE /@id', function ($id) {
            Flight::jsonResponse(Flight::UserController()->delete($id), 200, JSON_PRETTY_PRINT);
        });

    });




    //Bookings
    Flight::group('/bookings', function () {

        Flight::route('/', function () {
            Flight::jsonResponse(Flight::BookingController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {
            Flight::jsonResponse(Flight::BookingController()->show($id), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::BookingController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::BookingController()->delete($id);
            Flight::jsonResponse($delete, 200, JSON_PRETTY_PRINT);
        });

    });


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
        });

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::RatingController()->delete($id);
            Flight::jsonResponse($delete, 200, JSON_PRETTY_PRINT);
        });

    });


    //Services
    Flight::group('/services', function () {

        Flight::route('/', function () {
            Flight::jsonResponse(Flight::ServiceController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {
            Flight::jsonResponse(Flight::ServiceController()->show($id), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::ServiceController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::ServiceController()->delete($id);
            Flight::jsonResponse($delete, 200, JSON_PRETTY_PRINT);
        });

    });


    //Tenants
    Flight::group('/tenants', function () {

        Flight::route('/', function () {
            Flight::jsonResponse(Flight::TenantController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id) {
            Flight::jsonResponse(Flight::TenantController()->show($id), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id) {
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::TenantController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('DELETE /@id', function ($id) {
            $delete = Flight::TenantController()->delete($id);
            Flight::jsonResponse($delete, 200, JSON_PRETTY_PRINT);
        });

    });


});
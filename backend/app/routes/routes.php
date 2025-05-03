<?php

namespace App\Routes;

use App\Controller\UserController;
use Flight;
use Flight\Engine;
use http\Env\Request;
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

    Flight::group('/users', function () {


        Flight::route('/', function (){
            Flight::jsonResponse(Flight::UserController()->index(), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('GET /@id', function ($id){
            Flight::jsonResponse(Flight::UserController()->show($id), 200, JSON_PRETTY_PRINT);
        });

        Flight::route('POST /@id', function ($id){
            $data = Flight::request()->data;
            if ($data->id != $id)
                throw new IllogicalValuesException("ID value missmatch");

            Flight::jsonResponse(Flight::UserController()->update($data->getData()), 200, JSON_PRETTY_PRINT);
        });


        Flight::route('DELETE /@id', function ($id){
            Flight::jsonResponse(Flight::UserController()->delete($id), 200, JSON_PRETTY_PRINT);
        });






    });



});
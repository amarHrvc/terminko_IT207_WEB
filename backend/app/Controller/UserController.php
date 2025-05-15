<?php

namespace App\Controller;

use Flight;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Users", description: "Operations related to users")]
class UserController extends BaseController
{
    public function __construct()
    {
        $userDao = Flight::UserDao();
        parent::__construct($userDao);
    }

//    #[OA\Get(path: '/api/v1/users', operationId: 'get All users ')]
//    #[OA\Response(response: '200', description: 'users')]

//    #[OA\Get(
//        path: '/api/users',
//        responses: [
//            new OA\Response(response: 200, description: 'AOK'),
//            new OA\Response(response: 401, description: 'Not allowed'),
//        ]
//    )]

    #[OA\Get(
        path: '/api/v1/users',
        operationId: 'getAllUsers',
        description: 'Returns a list of all users',
        summary: 'Get all users',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of users',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items()
                )
            )
        ]
    )]
    public function index()
    {

        $users = $this->dao->findAll();
        return (Flight::getArrayFromModels($users));
    }


    #[OA\Get(
        path: '/api/v1/users/{id}',
        operationId: 'getUserById',
        description: 'Returns a single user by ID',
        summary: 'Get user by ID',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of user to return',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User found',
                content: new OA\JsonContent(ref: 'array')
            ),
            new OA\Response(
                response: 404,
                description: 'User not found'
            )
        ]
    )]
    public function show(string $id): array
    {
        $userById = Flight::UserDao()->findById($id);
        return ($userById ? $userById->toArray() : []);

    }

    public function update(array $data)
    {
        return Flight::UserDao()->update($data['id'], $data);
    }
}

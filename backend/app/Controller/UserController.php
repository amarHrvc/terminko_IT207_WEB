<?php

namespace App\Controller;

use Firebase\JWT\JWT;
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

    #[OA\Put(
        path: '/api/v1/users/{id}',
        operationId: 'updateUser',
        description: 'Updates a user by ID',
        summary: 'Update user by ID',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of user to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            example: 'John Doe'
                        ),
                        new OA\Property(
                            property: 'email',
                            type: 'string',
                            example: 'BZ2d0@example.com'
                        ),
                        new OA\Property(
                            property: 'password',
                            type: 'string',
                            example: 'password123'
                        )
                    ],
                    type: 'object'
                )
            )],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'id',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            example: 'John Doe'
                        ),
                        new OA\Property(
                            property: 'email',
                            type: 'string',
                            example: 'BZ2d0@example.com'
                        )]
                )
            )
        ]
    )]
    public function update(array $data)
    {
        return Flight::UserDao()->update($data['id'], $data);
    }


    #[OA\Post(
        path: '/api/v1/register',
        operationId: 'registerUser',
        description: 'Registers a new user',
        summary: 'Register user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'John Doe'
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        example: 'BZ2d0@example.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'password123'
                    )
                ],
                type: 'object'
            ),
        ),
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User registered',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'id',
                            type: 'string',
                            example: '1'
                        )
                    ]
                )
            )
        ]
    )]
    public function register($data): array
    {

        if (!isset($data['password']) || !$data['email']) {
            return ['error' => 'Missing required fields'];
        }

        $existingUser = Flight::UserDao()->findByEmail($data['email']);
        if ($existingUser) {
            return ['error' => 'User already exists!'];
        }


        $userId = Flight::UserDao()->create($data);
        $user = Flight::UserDao()->findById($userId);
        return ['user' => $user->toArray()];
    }

    #[OA\Post(
        path: '/api/v1/users/login',
        operationId: 'loginUser',
        description: 'Logs in a user',
        summary: 'Login user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        example: 'BZ2d0@example.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'password123'
                    )
                ]
            )
        ),
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User logged in',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'id',
                            type: 'string',
                            example: '1'
                        ),
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            example: 'John Doe'
                        ),
                        new OA\Property(
                            property: 'email',
                            type: 'string',
                            example: 'BZ2d0@example.com'
                        ),
                        new OA\Property(
                            property: 'token',
                            type: 'string',
                            example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9'
                        )
                    ]
                )
            )
        ]
    )]
    public function login($data): array
    {
        $user = Flight::UserDao()->findByEmail($data['email']);

        if (!$user) {
            return ['error' => 'User not found'];
        }

        if (!password_verify($data['password'], $user->getPassword())) {
            return ['error' => 'Invalid credentials'];
        }

        unset($data['password']);

        $token_data = [
            'user' => $user,
            'exp' => time() + (60 * 60 * 24),
            'iat' => time()
        ];

        $token = JWT::encode($token_data, $_ENV['JWT_SECRET'], 'HS256');

        return ['user' => array_merge($user->toArray(), ['token' => $token])];
    }


    #[OA\Post(
        path: '/api/v1/users',
        operationId: 'createUser',
        description: 'registers a new user',
        summary: 'Register user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'John Doe'
                    ),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        example: 'BZ2d0@example.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'password123'
                    )
                ],
            )
        ),
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'id',
                            type: 'string',
                            example: '1'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'error',
                            type: 'string',
                            example: 'User already exists!'
                        )
                    ]
                )
            )
        ]
    )]
    public function createUser(array $data): array
    {
        $user = Flight::UserDao()->findByEmail($data['email']);
        if ($user) {
            return ['error' => 'User already exists!'];
        }

        return ['user' => Flight::UserDao()->create($data)];
    }
}

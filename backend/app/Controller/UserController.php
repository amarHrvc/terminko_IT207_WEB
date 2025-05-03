<?php

namespace App\Controller;

use Flight;

class UserController extends BaseController
{
    public function __construct()
    {
        $userDao = Flight::UserDao();
        parent::__construct($userDao);
    }

    public function index()
    {

        $users = $this->dao->findAll();
        return (Flight::getArrayFromModels($users));

    }

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

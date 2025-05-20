<?php

namespace App\Controller;

use Flight;

class TenantController extends BaseController
{
    public function __construct()
    {
        $dao = Flight::TenantDao();
        parent::__construct($dao);
    }

    public function index()
    {

        $tenants = $this->dao->findAll();
        return (Flight::getArrayFromModels($tenants));

    }

    public function show(string $id): array
    {
        $byId = $this->dao->findById($id);
        return ($byId ? $byId->toArray() : []);

    }

    public function update(array $data)
    {
        return $this->dao->update($data['id'], $data);
    }

}

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

    
    public function create($data)
    {
        if (empty($data['name']) || empty($data['email'])) {
            return \App\Helpers\Helpers::getResponseObject(['success' => false, 'message' => "Name and email are required"],  400);
        }

        // Check if tenant with the same email already exists
        $existingTenant = $this->dao->findByEmail($data['email']);
        if ($existingTenant) {
            return \App\Helpers\Helpers::getResponseObject(['success' => false, 'error'=>true, 'message'=>"Tenant with this email already exists"], 400);
            
        }
        return parent::create($data) ? 
            \App\Helpers\Helpers::getResponseObject(['success' => true, 'message' => "Tenant created successfully"], 201) :
            \App\Helpers\Helpers::getResponseObject(['success' => false, 'message' => "Error while creating tenant"], 500);
    }

}

<?php

namespace App\Dao;

use App\Models\Service;

class ServiceDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'services';
        $this->modelClass = Service::class;
    }

}
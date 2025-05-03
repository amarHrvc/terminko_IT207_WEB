<?php

namespace App\Controller;

use App\Dao\BaseDao;

class BaseController
{
    protected $dao;

    public function __construct(BaseDao $dao)
    {
        $this->dao = $dao;
    }

    public function findAll()
    {
        return $this->dao->findAll();

    }

    public function findById($id)
    {
        return $this->dao->findById($id);

    }


    public function create($data)
    {
        return $this->dao->create($data);

    }
    public function update(array $data)
    {
        return $this->dao->update($data['id'], $data);

    }
    public function delete($id)
    {
        return $this->dao->delete($id);

    }


}
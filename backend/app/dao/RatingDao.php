<?php

namespace App\Dao;

use App\Models\Rating;

class RatingDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ratings';
        $this->modelClass = Rating::class;
    }
}
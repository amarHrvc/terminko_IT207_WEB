<?php

namespace App\Models\Factories;

use App\Models\Tenant;

class TenantFactory
{
    public function create($data)
    {
        $tenant = Tenant::fromArray($data);

        return $tenant;
    }

}
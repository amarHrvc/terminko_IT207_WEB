<?php

namespace App\Models;

class User
{
    private $id;
    private $tenant_id;
    private $name;
    private $email;
    private $password_hash;
    private $phone;
    private $role;
    private $created_at;
    private $updated_at;

    /**
     * @param $id
     * @param $tenant_id
     * @param $name
     * @param $email
     * @param $password_hash
     * @param $phone
     * @param $role
     * @param $created_at
     * @param $updated_at
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->tenant_id = $data['tenant_id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password_hash = $data['password_hash'];
        $this->phone = $data['phone'];
        $this->role = $data['role'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTenantId()
    {
        return $this->tenant_id;
    }

    /**
     * @param mixed $tenant_id
     */
    public function setTenantId($tenant_id): void
    {
        $this->tenant_id = $tenant_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * @param mixed $password_hash
     */
    public function setPasswordHash($password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}

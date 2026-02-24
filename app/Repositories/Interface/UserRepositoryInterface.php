<?php

namespace App\Repositories\Interface;

interface UserRepositoryInterface
{
    public function find($id);

    public function create(array $data);

    public function update($id, array $data);
}

<?php

namespace App\Repositories\Interface;

interface BookRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function paginateWithSearch(int $size, array $filters = []);
}
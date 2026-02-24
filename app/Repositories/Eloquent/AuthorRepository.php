<?php

namespace  App\Repositories\Eloquent;

use App\Models\Author;
use App\Repositories\Interface\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    protected $model;

    public function __construct(Author $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }
}

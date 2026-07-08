<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderImport;
use App\Repositories\Interface\OrderImportRepositoryInterface;

class OrderImportRepository extends BaseRepository implements OrderImportRepositoryInterface
{
    public function __construct(OrderImport $model)
    {
        parent::__construct($model);
    }

    public function paginateWithUser(int $size)
    {
        return $this->model
            ->with('user')
            ->latest()
            ->paginate($size);
    }

    public function findMany(array $ids)
    {
        return $this->model
            ->whereIn('id', $ids)
            ->get();
    }
}

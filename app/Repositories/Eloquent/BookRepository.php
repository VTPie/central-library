<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\BaseRepository;
use App\Models\Book;
use App\Repositories\Interface\BookRepositoryInterface;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }

    public function paginateWithSearch(int $size, array $filters = [])
    {
        $query = $this->model->with(['author', 'category']);
        if (!empty($filters['book_title'])) {
            $query->where('title', 'like', '%' . $filters['book_title'] . '%');
        }
        if (!empty($filters['author_id']) && (int)$filters['author_id'] > 0) {
            $query->where('author_id', (int)$filters['author_id']);
        }
        if (!empty($filters['category_id']) && (int)$filters['category_id'] > 0) {
            $query->where('category_id', (int)$filters['category_id']);
        }

        return $query->paginate($size)->withQueryString();
    }
}
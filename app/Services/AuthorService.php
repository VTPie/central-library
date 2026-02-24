<?php

namespace App\Services;

use App\Repositories\Interface\AuthorRepositoryInterface;
use Illuminate\Support\Facades\Log;

class AuthorService
{
    protected AuthorRepositoryInterface $authorRepo;

    public function __construct(AuthorRepositoryInterface $authorRepo)
    {
        $this->authorRepo = $authorRepo;
    }

    public function getAllAuthors()
    {
        try {
            return $this->authorRepo->all();
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}

<?php

namespace App\Services;

use App\Repositories\Interface\OrderImportRepositoryInterface;
use Illuminate\Support\Facades\Log;

class OrderImportService
{
    protected OrderImportRepositoryInterface $orderImportRepo;

    public function __construct(OrderImportRepositoryInterface $orderImportRepo)
    {
        $this->orderImportRepo = $orderImportRepo;
    }

    public function getImportHistory(int $size = 20)
    {
        try {
            return $this->orderImportRepo->paginateWithUser($size);
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function createImportRecord(array $data)
    {
        try {
            return $this->orderImportRepo->create($data);
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getProgress(array $ids)
    {
        try {
            return $this->orderImportRepo->findMany($ids);
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

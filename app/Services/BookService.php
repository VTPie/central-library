<?php

namespace App\Services;

use App\Repositories\Interface\BookRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookService
{
    protected BookRepositoryInterface $bookRepo;

    public function __construct(BookRepositoryInterface $bookRepo)
    {
        $this->bookRepo = $bookRepo;
    }

    public function getAllBooks($request)
    {
        try {
            return $this->bookRepo->paginateWithSearch(8, $request->only(['book_title', 'category_id', 'author_id']));
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function deleteBook($id)
    {
        try {
            $target_book = $this->bookRepo->find($id);
            Storage::disk('public')->delete($target_book->cover_url);
    
            return $this->bookRepo->delete($id);
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function storeBook(array $data)
    {
        try {
            $file = $data['cover_url'];
            $fileName = 'book' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('cover_img', $fileName, 'public');
            $data['cover_url'] = $path;
    
            return $this->bookRepo->create($data);
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function findBookById($id)
    {
        try {
            return $this->bookRepo->find($id);
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateBook(int $id, array $data)
    {
        try {
            $target_book = $this->bookRepo->find($id);
            Storage::disk('public')->delete($target_book->cover_url);
    
            $file = $data['cover_url'];
            $fileName = 'book' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('cover_img', $fileName, 'public');
            $data['cover_url'] = $path;
    
            return $this->bookRepo->update($id, $data);
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateBookWithoutCoverImg(int $id, array $data)
    {
        try {
            return $this->bookRepo->update($id, $data);
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
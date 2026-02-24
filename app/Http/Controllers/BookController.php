<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use App\Services\AuthorService;
use App\Services\CategoryService;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected BookService $bookService;
    protected AuthorService $authorService;
    protected CategoryService $categoryService;

    public function __construct(
        BookService $bookService,
        AuthorService $authorService,
        CategoryService $categoryService
    ) {
        $this->bookService = $bookService;
        $this->authorService = $authorService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        try {
            $books = $this->bookService->getAllBooks($request);
    
            return view('books.index', [
                'books' => $books,
                'author_list' => $this->authorService->getAllAuthors(),
                'category_list' => $this->categoryService->getAllCategories(),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect('/')->withErrors('Cannot get the book list right now. Please try again later.');
        }
    }

    public function add()
    {
        try {
            return view('books.book-form', [
                'action' => route('books.store'),
                'method' => 'POST',
                'title' => 'Add New Book',
                'author_list' => $this->authorService->getAllAuthors(),
                'category_list' => $this->categoryService->getAllCategories(),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('books.index')->with('error', 'Cannot add new book right now. Please try again later.');
        }
    }

    public function store(StoreBookRequest $request)
    {
        try {
            $data = $request->validated();
            $this->bookService->storeBook($data);
    
            return redirect()->route('books.index')->with('success', 'Book created successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('books.index')->with('error', 'Failed to create the book. Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            return view('books.book-form', [
                'title' => 'Edit Book',
                'method' => 'PUT',
                'action' => route('books.update', $id),
                'author_list' => $this->authorService->getAllAuthors(),
                'category_list' => $this->categoryService->getAllCategories(),
                'book' => $this->bookService->findBookById($id),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('books.index')->with('error', 'Cannot edit book right now. Please try again later.');
        }
    }

    public function update(UpdateBookRequest $request, int $id)
    {
        try {
            $data = $request->validated();
            if(isset($data['cover_url'])){
                $this->bookService->updateBook($id, $data);
            } else {
                $this->bookService->updateBookWithoutCoverImg($id, $data);
            }
    
            return redirect()->route('books.index')->with('success', 'Book edited successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('books.index')->with('error', 'Failed to edit the book. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->bookService->deleteBook($id);

            return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('books.index')->with('error', 'Failed to delete the book. Please try again.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\AuthorService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    protected AuthorService $authorService;

    public function __construct(AuthorService $authorService) {
        $this->authorService = $authorService;
    }

    public function index(Request $request)
    {
        try {
            // Cache the author list for 60 seconds to reduce database load
            $authorList = Cache::remember('authors', 60, function () {
                return $this->authorService->getAllAuthors();
            });

            return view('authors.index', compact('authorList'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect('/')->withErrors('Cannot get the author list right now. Please try again later.');
        }
    }
}

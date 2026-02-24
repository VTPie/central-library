@extends('layout')

@section('title', 'Central Library | Book List')

@section('content')
<div class="container">
    <h3 class="text-center mt-2">Book List</h3>
    @foreach (['success' => 'success', 'error' => 'danger'] as $msg => $type)
        @if (session($msg))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach
    <a href="{{ route('books.add') }}" class="btn btn-primary">Add new book</a>

    <div class="accordion mt-3" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Filter & Search
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <form class="accordion-body" method="GET" action="{{ route('books.index') }}">
                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="book_title" class="form-label">Book title</label>
                            <input name="book_title" id="book_title" class="form-control" type="text" placeholder="Search by title" aria-label="default input example">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="author_id" class="form-label">Author</label>
                            <select id="author_id" name="author_id" class="form-select" aria-label="Default select example">
                                <option selected value=0>Select author</option>
                                @foreach ($author_list as $author)   
                                    <option value={{ $author->id }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="category_id" class="form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-select" aria-label="Default select example">
                                <option selected value=0>Select category</option>
                                @foreach ($category_list as $category)
                                    <option value={{ $category->id }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-outline-primary" type="submit" id="filter-btn">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        @if (count($books) > 0)
            @foreach($books as $book)
                <div class="col-md-3 g-4">
                    @include('books.book-card', ['book' => $book])
                </div>
            @endforeach
            <nav aria-label="pagination">
                <ul class="pagination mb-0 d-flex justify-content-center">
                    <li class="page-item {{ $books->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $books->previousPageUrl() }}">Previous</a>
                    </li>
                    @for ($i = 1; $i <= $books->lastPage(); $i++)
                        <li class="page-item {{ $books->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $books->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $books->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $books->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        @else
            <p class="text-center mt-2">No books found!</p>
        @endif
    </div>
</div>
@endsection

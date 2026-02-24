@php
    $isEdit = isset($book);
@endphp
@extends('layout')

@section('title', 'Central Library | ' . $title)

@section('content')
<div class="container">
    <h3 class="text-center mt-2">{{$title}}</h3>
    @if ($errors->any())
        <div class="alert alert-danger w-50 mx-auto">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="w-50 mx-auto" action="{{ $action }}" method='POST' enctype="multipart/form-data">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="mb-3">
            <label for="title" class="form-label">Book title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Input book title"
                value="{{ $book->title ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="cover_url" class="form-label">Cover Image</label>
            <input type="file" class="form-control" name="cover_url" id="cover_url">
        </div>

        <div class="mb-3">
            <label for="author_id" class="form-label">Author</label>
            <select id="author_id" name="author_id" class="form-select" aria-label="Default select example">
                @foreach ($author_list as $author)   
                    <option 
                    value={{ $author->id }}
                    @if ($isEdit && $book->author_id == $author->id)
                        selected
                    @endif
                    >
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select" aria-label="Default select example">
                @foreach ($category_list as $category)   
                    <option 
                    value={{ $category->id }}
                    @if ($isEdit && $book->category_id == $category->id)
                        selected
                    @endif
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="published_year" class="form-label">Published year</label>
            <input type="number" class="form-control" name="published_year" id="published_year" placeholder="Input published year"
                value="{{ $book->published_year ?? '' }}">
        </div>

        <div class='d-flex justify-content-center'>
            <a href="{{ route('books.index') }}" class="me-2 btn btn-secondary">Cancel</a>
            <button type='submit' class="ms-2 btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection

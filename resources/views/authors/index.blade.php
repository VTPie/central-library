@extends('layout')

@section('title', 'Central Library | Author List')

@section('content')
<div class="container">
    <h3 class="text-center mt-2">Author List</h3>
    <div class="row">
        @foreach($authorList as $author)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $author->name }}</h5>
                        <p class="card-text">{{ $author->biography }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@extends('layout')

@section('title', 'Central Library | ' . $title)

@section('content')
<div class="container">
    <h3 class="text-center mt-2">{{ isset($order) ? 'Edit Order' : 'Add New Order' }}</h3>

    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
            <form method="POST"
                  action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}">
                @csrf
                @if (isset($order))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="book_id" class="form-label">Book</label>
                    <select name="book_id" id="book_id" class="form-select @error('book_id') is-invalid @enderror">
                        <option value="">— Select a book —</option>
                        @foreach ($book_list as $book)
                            <option value="{{ $book->id }}"
                                {{ old('book_id', $order->book_id ?? '') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('book_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" min="1" name="quantity" id="quantity"
                           class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', $order->quantity ?? '') }}">
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($order) ? 'Update Order' : 'Create Order' }}
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

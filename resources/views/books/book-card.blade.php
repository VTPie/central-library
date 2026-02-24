<div class="card border-0" style="width: 15rem; height: 38rem">
  <img src={{  asset('storage/' . $book->cover_url) }} class="card-img-top" alt="..." style="height: 65%">
  <div class="card-body">
    <h5 class="card-title text-center">{{ $book->title }}</h5>
    <p class="card-text">
        <strong>Author:</strong> {{ $book->author->name ?? 'N/A' }}<br>
        <strong>Category:</strong> {{ $book->category->name ?? 'N/A' }}<br>
        <strong>Published:</strong> {{ $book->published_year ?? 'Unknown' }}<br>
    </p>
    <div class="d-flex justify-content-around">
        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline-warning">Edit</a>
        <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirmDelete()">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
    </div>
  </div>
</div>

<script>
  function confirmDelete() {
    return confirm('Do you really want to delete this book?');
  }
</script>

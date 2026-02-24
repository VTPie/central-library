<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Central Library')</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section class="mx-auto mt-5 border p-3 d-flex justify-content-center pb-4 rounded" style="width: 28rem;">
      <form style="width: 22rem;" method="POST" action="{{ route('register') }}">
        @csrf
        <h3 class="text-center mb-4">Register</h5>
        @if ($errors->any())
          <div style="color:red;">
              {{ $errors->first() }}
          </div>
        @endif
        <div class="form-floating mb-4">
          <input type="name" name="name" id="name" class="form-control" placeholder="Enter your name" required>
          <label for="email">Name</label>
        </div>
        <div class="form-floating mb-4">
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
          <label for="email">Email address</label>
        </div>
        <div class="form-floating mb-4">
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
          <label for="email">Password</label>
        </div>
        <div class="form-floating mb-4">
          <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Enter your password confirmation" required>
          <label for="password_confirmation">Password Confirmation</label>
        </div>

        <button type="submit" class="btn btn-primary d-block mx-auto">Register</button>
      </form>
    </section>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

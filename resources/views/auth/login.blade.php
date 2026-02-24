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
      <form style="width: 22rem;" method="POST" action="{{ route('login') }}">
        @csrf
        <h3 class="text-center mb-4">Login</h5>
        @foreach (['success' => 'success', 'error' => 'danger'] as $msg => $type)
          @if (session($msg))
              <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                  {{ session($msg) }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          @endif
        @endforeach
        <div class="form-floating mb-4">
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
          <label for="email">Email address</label>
        </div>
        <div class="form-floating mb-4">
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
          <label for="password">Password</label>
        </div>

        <div class="row mb-4 d-flex justify-content-center">
          <div class="col d-flex justify-content-center">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked="" disabled>
              <label class="form-check-label" for="form2Example31"> Remember me </label>
            </div>
          </div>
          <div class="col">
            <a href="#!" style="pointer-events:none; color:gray;">Forgot password?</a>
          </div>
        </div>

        <button type="submit" class="btn btn-primary d-block mx-auto">Sign in</button>
        <p class="mb-0 mt-3 text-black text-opacity-50">
          Don't have an account?
          <span>
            <a class="text-primary text-opacity-75" href="{{ route('register') }}">Create an account now</a>
          </span>
        </p>
      </form>
    </section>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

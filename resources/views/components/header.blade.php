<nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;" data-bs-theme="light">
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo.svg" alt="Bootstrap" width="36" height="30">
    </a>

    <div class="collapse navbar-collapse" id="navbarColor03">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->is('/') ? 'active fw-bold' : '' }}" href="/" @if(request()->is('/')) aria-current="page" @endif>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('books*') ? 'active fw-bold' : '' }}" href="/books" @if(request()->is('books*')) aria-current="page" @endif>Books</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('authors*') ? 'active fw-bold' : '' }}" href="{{ route('authors.index') }}" @if(request()->is('authors*')) aria-current="page" @endif>Authors</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('orders*') ? 'active fw-bold' : '' }}" href="{{ route('orders.index') }}" @if(request()->is('orders*')) aria-current="page" @endif>Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#">About us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#">Contact</a>
        </li>
      </ul>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>

      <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Account
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#" style="pointer-events:none; color:gray;">Profile</a></li>
          <li><a class="dropdown-item" onclick="document.getElementById('logout-form').submit();">Sign out</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

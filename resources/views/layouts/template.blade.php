<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apotek | App</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head> 
  <style>

    body {
      background-image: linear-gradient(45deg, blue, red);
      background-size: cover;
      background-attachment: fixed;
      margin: 0;
      padding: 0;
      background-size: 300% 300%;
      animation: color 3s ease-in-out infinite;
    }

    @keyframes color {
      0% {
        background-position: 0 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0 50%;
      }
    }
    
  </style>
  <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Apotek App</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
              @if (Auth::check())
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/dashboard">Dashboard</a>
              </li>
              {{-- cek value dari column role table users data yang login, kalo value rolenya admin, li dimunculkan --}}
              @if (Auth::user()->role == "admin")
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Obat
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('medicine.data') }}">Data Obat</a></li>
                        <li><a class="dropdown-item" href="{{ route('medicine.create') }}">Tambah Obat</a></li>
                        <li><a class="dropdown-item" href="{{ route('medicine.stock') }}">Stock Obat</a></li>
                    </ul>
                </li>
                @endif
              @if (Auth::user()->role == "cashier")
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('order.index') }}">Pembelian</a>
                </li>
                @endif
                @if (Auth::user()->role == 'admin')
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('data.order.index') }}">Data Pembelian</a>
                </li>
                @endif
                @if (Auth::user()->role == "admin")
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('account.user') }}">Kelola Akun</a>
                </li>
                @endif
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('auth-logout') }}">Logout</a>
                </li>
            </ul>
            @endif
          </div>
        </div>
      </nav>
    <div class="container">
        {{-- menyimpan html yang sifatnya dinamis/berubah tiap page nya --}}
        {{-- wajib diisi ketika template dipanggil --}}
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- untuk mengisi css/js yang sifatnya dinamis (optional) --}}
    {{-- diisi dengan @push --}}
    @stack('script')
  </body>
</html>
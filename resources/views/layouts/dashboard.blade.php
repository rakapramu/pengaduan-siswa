<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- csrf --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - {{ ucwords($title) ?? config('app.name', 'Laravel') }}</title>
    @include('includes.style')
    @stack('css')
</head>

<body>
    <script src="{{ asset('dashboard') }}/static/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar">
            @include('includes.sidebar')
        </div>
        <div id="main">
            <header class="mb-3 d-flex align-items-center justify-content-between">
                <!-- Burger Button -->
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>

                <!-- Notification Bell -->
                <div class="dropdown me-4 ms-auto">
                    <a href="#" class="text-dark" id="notifDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-bell fs-4"></i>

                        <span class="badge bg-danger badge-counter"
                            style="position:absolute; top:2px; font-size: 10px;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </a>

                    <!-- Dropdown list -->
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="width: 300px;">
                        <li class="dropdown-header">Notifikasi</li>

                        @forelse(auth()->user()->unreadNotifications as $item)
                            <li class="dropdown-item" style="border-bottom: 1px solid #eee;">
                                <div class="d-flex flex-column">

                                    <!-- Pesan notifikasi -->
                                    <span class="fw-bold small text-wrap" style="white-space: normal;">
                                        {{ $item->data['message'] }}
                                    </span>
                                    <!-- Link lihat & baca -->
                                    <a href="{{ $item->data['route'] . '?id=' . $item->id }}"
                                        class="text-primary small mt-1">
                                        Lihat dan baca →
                                    </a>

                                    <!-- Timestamp -->
                                    <span class="text-muted small mt-1">
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>

                                </div>
                            </li>

                        @empty
                            <li>
                                <p class="dropdown-item text-center text-muted">Tidak ada notifikasi</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </header>

            @yield('content')

            @include('includes.footer')
        </div>
    </div>
    @include('includes.script')
    <script>
        flatpickr('.flatpickr', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        })
    </script>
    @if (session()->has('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: "#4fbe87",
            }).showToast()
        </script>
    @endif
    @stack('js')
</body>

</html>

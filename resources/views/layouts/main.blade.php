<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Sekolah - @yield('title')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="app-container">
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <button class="btn-close-sidebar" onclick="toggleSidebar()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
                
                <div class="logo-icon">
                    <ion-icon name="school-outline"></ion-icon>
                </div>
                <div class="logo-text">
                    <h2>E-Absensi</h2>
                    <span>Projek Absen by: 2306018</span>
                </div>
            </div>
            
            <nav class="sidebar-menu">
                <ul>
                    @if(Auth::user()->role == 'admin')
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <ion-icon name="grid-outline"></ion-icon>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.guru.index') }}">
                                <ion-icon name="people-outline"></ion-icon>
                                <span>Data Guru</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.mapel.index') }}">
                                <ion-icon name="book-outline"></ion-icon>
                                <span>Mata Pelajaran</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.tahun.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tahun.index') }}">
                                <ion-icon name="calendar-number-outline"></ion-icon>
                                <span>Tahun Ajaran</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kelas.index') }}">
                                <ion-icon name="easel-outline"></ion-icon>
                                <span>Data Kelas</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.siswa.index') }}">
                                <ion-icon name="accessibility-outline"></ion-icon>
                                <span>Data Siswa</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.jadwal.index') }}">
                                <ion-icon name="calendar-outline"></ion-icon>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.rekap.index') }}">
                                <ion-icon name="document-text-outline"></ion-icon>
                                <span>Rekap Laporan</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.promosi.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.promosi.index') }}">
                                <ion-icon name="trending-up-outline"></ion-icon>
                                <span>Kenaikan Kelas</span>
                            </a>
                        </li>
                        @else
                        <li class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('guru.dashboard') }}">
                                <ion-icon name="grid-outline"></ion-icon>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('guru.rekap') ? 'active' : '' }}">
                            <a href="{{ route('guru.rekap') }}">
                                <ion-icon name="stats-chart-outline"></ion-icon>
                                <span>Rekap Absensi</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="user-details">
                        <h4>{{ Auth::user()->name }}</h4>
                        <small>{{ ucfirst(Auth::user()->role) }}</small>
                    </div>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" title="Keluar">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                    <ion-icon name="menu-outline"></ion-icon>
                </button>

                <div class="page-title">
                    <h1>@yield('header-title')</h1>
                    <p class="date-now">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="search-box">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" placeholder="Cari data...">
                </div>
            </header>

            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    </script>

</body>
</html>
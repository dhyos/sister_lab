<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <div class="bg-primary text-white rounded p-2 fw-bold">TI</div>
            <div class="d-flex flex-column lh-sm">
                <span class="fw-bold">Laboratorium</span>
                <small class="text-white-50" style="font-size: 0.7rem;">Teknik Informatika UTM</small>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                
                @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.lab.*') ? 'active' : '' }}" href="{{ route('admin.lab.index') }}">
                            <i class="bi bi-folder-check"></i> Kelola Pengajuan Lab
                        </a>
                    </li>
                @endif
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}" href="{{ route('peminjaman.riwayat') }}">
                        <i class="bi bi-clock-history"></i> Riwayat Peminjaman
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted">Login sebagai</small>
                            <div class="fw-bold text-truncate" style="max-width: 200px;">{{ Auth::user()->email }}</div>
                            @if(Auth::user()->role === 'admin')
                                <small class="badge bg-success mt-1">Administrator</small>
                            @endif
                        </li>
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('peminjaman.riwayat') }}">
                                <i class="bi bi-clock-history me-2"></i> Riwayat Peminjaman
                            </a>
                        </li>
                        
                        @if(Auth::user()->role === 'admin')
                            <li><hr class="dropdown-divider"></li>
                            <li class="px-3 py-1">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Admin Menu</small>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.lab.index') }}">
                                    <i class="bi bi-folder-check me-2"></i> Kelola Pengajuan Lab
                                </a>
                            </li>
                        @endif
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-gear me-2"></i> Edit Profil
                            </a>
                        </li>
                        
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
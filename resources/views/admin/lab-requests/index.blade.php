@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Kelola Pengajuan Laboratorium</h2>
            <p class="text-muted mb-0">Kelola semua pengajuan penggunaan laboratorium</p>
        </div>
        <div>
            <span class="badge bg-primary fs-6">Total: {{ $stats['total'] }} Pengajuan</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="row mb-4">
        <div class="col-md mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Pengajuan</div>
                    <div class="display-6 fw-bold text-primary">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md mb-3">
            <div class="card shadow-sm border-warning border-2 h-100">
                <div class="card-body text-center">
                    <div class="text-warning small">Pending</div>
                    <div class="display-6 fw-bold text-warning">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md mb-3">
            <div class="card shadow-sm border-success border-2 h-100">
                <div class="card-body text-center">
                    <div class="text-success small">Disetujui</div>
                    <div class="display-6 fw-bold text-success">{{ $stats['disetujui'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md mb-3">
            <div class="card shadow-sm border-danger border-2 h-100">
                <div class="card-body text-center">
                    <div class="text-danger small">Ditolak</div>
                    <div class="display-6 fw-bold text-danger">{{ $stats['ditolak'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md mb-3">
            <div class="card shadow-sm border-info border-2 h-100">
                <div class="card-body text-center">
                    <div class="text-info small">Selesai</div>
                    <div class="display-6 fw-bold text-info">{{ $stats['selesai'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-2">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.lab.index', ['status' => 'semua']) }}" 
                   class="btn btn-sm {{ $status === 'semua' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.lab.index', ['status' => 'pending']) }}" 
                   class="btn btn-sm {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                    Pending ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('admin.lab.index', ['status' => 'disetujui']) }}" 
                   class="btn btn-sm {{ $status === 'disetujui' ? 'btn-success' : 'btn-outline-success' }}">
                    Disetujui ({{ $stats['disetujui'] }})
                </a>
                <a href="{{ route('admin.lab.index', ['status' => 'ditolak']) }}" 
                   class="btn btn-sm {{ $status === 'ditolak' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Ditolak ({{ $stats['ditolak'] }})
                </a>
                <a href="{{ route('admin.lab.index', ['status' => 'selesai']) }}" 
                   class="btn btn-sm {{ $status === 'selesai' ? 'btn-info' : 'btn-outline-info' }}">
                    Selesai ({{ $stats['selesai'] }})
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel Pengajuan --}}
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th style="width: 200px;">Nama Pemohon</th>
                            <th style="width: 150px;">Laboratorium</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 130px;">Waktu</th>
                            <th style="width: 200px;">Kegiatan</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $index => $item)
                            <tr>
                                <td class="text-center fw-bold">{{ ($pengajuan->currentPage() - 1) * $pengajuan->perPage() + $index + 1 }}</td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $item->user->name ?? 'User Terhapus' }}</div>
                                            <small class="text-muted">{{ $item->user->email ?? '-' }}</small>
                                            @if($item->no_wa)
                                                <br><small class="text-muted"><i class="bi bi-telephone"></i> {{ $item->no_wa }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-door-open"></i> {{ $item->lab->nama_lab ?? 'Unknown' }}
                                    </span>
                                </td>
                                
                                <td>
                                    <i class="bi bi-calendar3"></i>
                                    {{ isset($item->jadwal->tanggal) ? \Carbon\Carbon::parse($item->jadwal->tanggal)->format('d M Y') : '-' }}
                                </td>
                                
                                <td>
                                    <i class="bi bi-clock"></i>
                                    {{ $item->jadwal->jam_mulai ?? '00:00' }} - {{ $item->jadwal->jam_selesai ?? '00:00' }}
                                </td>
                                
                                <td>
                                    <small>{{ Str::limit($item->kegiatan ?? 'Tidak ada keterangan', 40) }}</small>
                                </td>
                                
                                <td class="text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['class' => 'bg-warning text-dark', 'label' => 'Pending'],
                                            'disetujui' => ['class' => 'bg-success text-white', 'label' => 'Disetujui'],
                                            'ditolak' => ['class' => 'bg-danger text-white', 'label' => 'Ditolak'],
                                            'selesai' => ['class' => 'bg-info text-white', 'label' => 'Selesai']
                                        ];
                                        $config = $statusConfig[$item->status] ?? ['class' => 'bg-secondary text-white', 'label' => ucfirst($item->status)];
                                    @endphp
                                    <span class="badge {{ $config['class'] }} px-3 py-2">{{ $config['label'] }}</span>
                                </td>
                                
                                <td class="text-center">
                                    <a href="{{ route('admin.lab.show', $item->id) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2 mb-0">Tidak ada pengajuan {{ $status !== 'semua' ? $status : '' }}</p>
                                        <small>Data pengajuan akan muncul di sini</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if($pengajuan->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $pengajuan->firstItem() }} - {{ $pengajuan->lastItem() }} dari {{ $pengajuan->total() }} pengajuan
                </div>
                <div>
                    {{ $pengajuan->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="mt-3 text-muted text-center">
        <small>
            <i class="bi bi-info-circle"></i> 
            Klik tombol "Detail" untuk melihat informasi lengkap dan mengubah status pengajuan
        </small>
    </div>
</div>

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.005);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>
@endsection
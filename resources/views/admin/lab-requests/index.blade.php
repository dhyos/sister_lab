@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Kelola Pengajuan Laboratorium</h2>
            <p class="text-muted mb-0">Kelola semua pengajuan penggunaan laboratorium</p>
        </div>
        <div>
            <span class="badge bg-primary fs-6">Total: {{ $requests->count() }} Pengajuan</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body py-2">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary active">
                    Semua ({{ $requests->count() }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning">
                    Pending ({{ $requests->where('status', 'pending')->count() }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-success">
                    Disetujui ({{ $requests->where('status', 'disetujui')->count() }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger">
                    Ditolak ({{ $requests->where('status', 'ditolak')->count() }})
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th style="width: 200px;">Nama Pemohon</th>
                            <th style="width: 120px;">NIM</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 130px;">Waktu</th>
                            <th style="width: 130px;">Ruangan</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $index => $req)
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($req->user->nama ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $req->user->nama ?? 'User Terhapus' }}</div>
                                            <small class="text-muted">{{ $req->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <span class="badge bg-secondary">{{ $req->user->nim ?? '-' }}</span>
                                </td>                             
                                <td>
                                    <i class="bi bi-calendar3"></i>
                                    {{ isset($req->jadwal->tanggal) ? \Carbon\Carbon::parse($req->jadwal->tanggal)->format('d M Y') : '-' }}
                                </td>
                                
                                <td>
                                    <i class="bi bi-clock"></i>
                                    {{ $req->jadwal->jam_mulai ?? '00:00' }} - {{ $req->jadwal->jam_selesai ?? '00:00' }}
                                </td>
                                
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-door-open"></i> {{ $req->lab->nama_lab ?? 'Unknown Lab' }}
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    @if($req->status == 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                                    @elseif($req->status == 'disetujui')
                                        <span class="badge bg-success px-3 py-2">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Ditolak</span>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.lab.show', $req->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2 mb-0">Belum ada pengajuan laboratorium</p>
                                        <small>Data pengajuan akan muncul di sini</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
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
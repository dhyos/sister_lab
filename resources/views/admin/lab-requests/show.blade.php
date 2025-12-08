@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.lab.index') }}">Kelola Lab</a></li>
            <li class="breadcrumb-item active">Detail Pengajuan #{{ $request->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <h2 class="mb-4">
                <i class="bi bi-file-earmark-text"></i> Detail Pengajuan Laboratorium
            </h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i> Informasi Pemohon
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-person"></i> Nama Pemohon</label>
                                <p class="fw-bold">{{ $request->user->nama ?? 'User Terhapus' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-credit-card-2-front"></i> NIM</label>
                                <p class="fw-bold">{{ $request->user->nim ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-building"></i> Jurusan</label>
                                <p class="fw-bold">{{ $request->user->jurusan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-envelope"></i> Email</label>
                                <p class="fw-bold">{{ $request->user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i> Detail Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-calendar3"></i> Tanggal Penggunaan</label>
                                <p class="fw-bold">
                                    {{ isset($request->jadwal->tanggal) ? \Carbon\Carbon::parse($request->jadwal->tanggal)->format('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-clock"></i> Waktu Penggunaan</label>
                                <p class="fw-bold">
                                    {{ $request->jadwal->jam_mulai ?? '00:00' }} - {{ $request->jadwal->jam_selesai ?? '00:00' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-door-open"></i> Ruangan</label>
                                <p class="fw-bold">{{ $request->lab->nama_lab ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-flag"></i> Status Saat Ini</label>
                                <p>
                                    @if($request->status == 'pending')
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">Pending</span>
                                    @elseif($request->status == 'disetujui')
                                        <span class="badge bg-success fs-6 px-3 py-2">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger fs-6 px-3 py-2">Ditolak</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info-item">
                                <label><i class="bi bi-file-text"></i> Keperluan</label>
                                <p class="fw-bold bg-light p-3 rounded">
                                    {{ $request->jadwal->kegiatan ?? 'Tidak ada keterangan' }}
                                </p>
                            </div>
                        </div>
                        
                        @if($request->alasan_penolakan)
                        <div class="col-md-12">
                            <div class="info-item">
                                <label><i class="bi bi-chat-left-quote"></i> Alasan Penolakan</label>
                                <p class="fw-bold bg-danger bg-opacity-10 p-3 rounded border-start border-danger border-4">
                                    {{ $request->alasan_penolakan }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.lab.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-lg sticky-top" style="top: 20px;">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Ubah Status Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lab.updateStatus', $request->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-toggle-on"></i> Status Pengajuan
                            </label>
                            <select name="status" class="form-select form-select-lg" required>
                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="disetujui" {{ $request->status == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                            <div class="form-text">Pilih status yang sesuai untuk pengajuan ini</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-pencil-square"></i> Catatan / Alasan Penolakan
                            </label>
                            <textarea 
                                name="alasan_penolakan" 
                                class="form-control" 
                                rows="5" 
                                placeholder="Contoh: Lab tersedia, disetujui. ATAU: Maaf jadwal bentrok..."
                            >{{ $request->alasan_penolakan }}</textarea>
                            <div class="form-text">Berikan alasan jika menolak, atau catatan jika menyetujui</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> 
                        Perubahan status akan langsung tersimpan dan dapat dilihat di halaman list
                    </small>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat</h6>
                </div>
                <div class="card-body">
                    <div class="timeline-item">
                        <small class="text-muted">
                            <i class="bi bi-calendar-check"></i> 
                            Diajukan pada: {{ $request->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .info-item {
        margin-bottom: 0.5rem;
    }
    
    .info-item label {
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .info-item p {
        margin: 0;
        color: #212529;
    }
    
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header {
        border: none;
        padding: 1rem 1.5rem;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #38ef7d;
        box-shadow: 0 0 0 0.2rem rgba(56, 239, 125, 0.25);
    }
</style>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.lab.index') }}">Kelola Lab</a></li>
            <li class="breadcrumb-item active">Detail Pengajuan #{{ $pengajuan->id }}</li>
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle"></i> <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Status Badge --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-semibold">Status Pengajuan</span>
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'bg-warning text-dark', 'label' => 'Menunggu Persetujuan'],
                                'disetujui' => ['class' => 'bg-success text-white', 'label' => 'Disetujui'],
                                'ditolak' => ['class' => 'bg-danger text-white', 'label' => 'Ditolak'],
                                'selesai' => ['class' => 'bg-info text-white', 'label' => 'Selesai']
                            ];
                            $config = $statusConfig[$pengajuan->status] ?? ['class' => 'bg-secondary text-white', 'label' => 'Unknown'];
                        @endphp
                        <span class="badge {{ $config['class'] }} fs-5 px-4 py-2">
                            {{ $config['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info Pemohon --}}
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
                                <label><i class="bi bi-person"></i> Nama Lengkap</label>
                                <p class="fw-bold">{{ $pengajuan->user->name ?? 'User Terhapus' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-envelope"></i> Email</label>
                                <p class="fw-bold">{{ $pengajuan->user->email ?? '-' }}</p>
                            </div>
                        </div>
                        @if($pengajuan->no_wa)
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-whatsapp"></i> No. WhatsApp</label>
                                <p class="fw-bold">{{ $pengajuan->no_wa }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-shield-check"></i> Role</label>
                                <p class="fw-bold">
                                    <span class="badge bg-secondary">{{ ucfirst($pengajuan->user->role ?? 'user') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Lab & Jadwal --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i> Detail Laboratorium & Jadwal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="info-item">
                                <label><i class="bi bi-door-open"></i> Nama Laboratorium</label>
                                <p class="fw-bold fs-5 text-primary">{{ $pengajuan->lab->nama_lab ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if($pengajuan->lab && $pengajuan->lab->lokasi)
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-geo-alt"></i> Lokasi</label>
                                <p class="fw-bold">{{ $pengajuan->lab->lokasi }}</p>
                            </div>
                        </div>
                        @endif

                        @if($pengajuan->lab && $pengajuan->lab->kapasitas)
                        <div class="col-md-6">
                            <div class="info-item">
                                <label><i class="bi bi-people"></i> Kapasitas</label>
                                <p class="fw-bold">{{ $pengajuan->lab->kapasitas }} orang</p>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-12">
                            <div class="alert alert-info border-info border-2 mb-0">
                                <strong><i class="bi bi-clock"></i> Jadwal Peminjaman:</strong>
                                @if($pengajuan->jadwal)
                                    <div class="mt-2">
                                        <p class="mb-1">
                                            <strong>Tanggal:</strong> 
                                            {{ \Carbon\Carbon::parse($pengajuan->jadwal->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Waktu:</strong> 
                                            {{ $pengajuan->jadwal->jam_mulai }} - {{ $pengajuan->jadwal->jam_selesai }} WIB
                                        </p>
                                    </div>
                                @else
                                    <p class="text-muted fst-italic mb-0">Tidak ada jadwal terkait</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Kegiatan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text"></i> Detail Kegiatan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <label><i class="bi bi-chat-square-text"></i> Deskripsi Kegiatan</label>
                        <div class="bg-light p-3 rounded border">
                            <p class="mb-0 fw-bold" style="white-space: pre-wrap;">{{ $pengajuan->kegiatan ?? 'Tidak ada deskripsi kegiatan' }}</p>
                        </div>
                    </div>

                    @if($pengajuan->surat_file)
                    <div class="mt-3">
                        <label><i class="bi bi-file-earmark-pdf"></i> Surat Pengajuan</label>
                        <div class="d-flex gap-2">
                            <a href="{{ Storage::url($pengajuan->surat_file) }}" 
                               target="_blank"
                               class="btn btn-outline-primary">
                                <i class="bi bi-eye"></i> Lihat Surat
                            </a>
                            <a href="{{ route('admin.lab.downloadSurat', $pengajuan->id) }}" 
                               class="btn btn-outline-success">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Alasan Penolakan --}}
            @if($pengajuan->status === 'ditolak' && $pengajuan->alasan_penolakan)
            <div class="alert alert-danger border-danger border-3" role="alert">
                <h5 class="alert-heading">
                    <i class="bi bi-x-circle"></i> Alasan Penolakan
                </h5>
                <hr>
                <p class="mb-0 fw-bold">{{ $pengajuan->alasan_penolakan }}</p>
            </div>
            @endif

            <a href="{{ route('admin.lab.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        {{-- Sidebar Kanan --}}
        <div class="col-lg-4">
            {{-- Form Update Status --}}
            <div class="card shadow-lg sticky-top" style="top: 20px;">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Ubah Status Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lab.updateStatus', $pengajuan->id) }}" method="POST" id="statusForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-toggle-on"></i> Status Pengajuan
                            </label>
                            <select name="status" id="statusSelect" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="disetujui" {{ $pengajuan->status == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                                <option value="selesai" {{ $pengajuan->status == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                            </select>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4" id="alasanContainer" style="display: none;">
                            <label class="form-label fw-bold">
                                <i class="bi bi-pencil-square"></i> Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                name="alasan_penolakan" 
                                id="alasanTextarea"
                                class="form-control" 
                                rows="5" 
                                placeholder="Jelaskan alasan penolakan secara detail..."
                            >{{ old('alasan_penolakan', $pengajuan->alasan_penolakan) }}</textarea>
                            @error('alasan_penolakan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Wajib diisi jika menolak pengajuan</div>
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
                        Perubahan status akan langsung tersimpan
                    </small>
                </div>
            </div>

            {{-- Quick Actions --}}
            @if($pengajuan->status === 'pending' || $pengajuan->no_wa)
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($pengajuan->status === 'pending')
                        <form action="{{ route('admin.lab.updateStatus', $pengajuan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="status" value="disetujui">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Setujui Langsung
                            </button>
                        </form>
                        @endif
                        
                        @if($pengajuan->no_wa)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengajuan->no_wa) }}" 
                           target="_blank"
                           class="btn btn-success w-100">
                            <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline-item mb-2">
                        <small class="text-muted d-block">
                            <i class="bi bi-calendar-plus"></i> 
                            <strong>Diajukan:</strong>
                        </small>
                        <small>{{ $pengajuan->created_at->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</small>
                    </div>
                    <div class="timeline-item">
                        <small class="text-muted d-block">
                            <i class="bi bi-pencil"></i> 
                            <strong>Terakhir Diupdate:</strong>
                        </small>
                        <small>{{ $pengajuan->updated_at->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</small>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('statusSelect');
    const alasanContainer = document.getElementById('alasanContainer');
    const alasanTextarea = document.getElementById('alasanTextarea');

    function toggleAlasan() {
        if (statusSelect.value === 'ditolak') {
            alasanContainer.style.display = 'block';
            alasanTextarea.required = true;
        } else {
            alasanContainer.style.display = 'none';
            alasanTextarea.required = false;
        }
    }

    statusSelect.addEventListener('change', toggleAlasan);
    toggleAlasan(); // Check initial state
});
</script>
@endsection
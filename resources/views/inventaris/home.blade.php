@extends('layout.app')

@section('judul', 'Data Inventaris Laboratorium')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3">Data Inventaris Laboratorium</h4>
        @include('komponen.alert')

        <a href="{{ route('inventaris.exportAllPdf') }}" class="btn btn-dark mb-3">
            <i class="fa-solid fa-file-pdf me-2"></i>PDF Semua Inventaris Lab
        </a>

        <!-- Lab Cards Grid -->
        <div class="row g-4">
            @forelse($lab as $index => $ruang)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('inventaris.index', ['id' => $ruang->id]) }}">
                        <div class="card lab-card h-75 shadow-sm">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <div class="lab-icon mb-3">
                                    <i class="fa-solid fa-computer text-primary" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="card-title text-center mb-3">{{ $ruang->nama_ruang }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="empty-state">
                                <i class="fa-solid fa-building text-muted mb-3" style="font-size: 4rem;"></i>
                                <h5 class="text-muted">Belum Ada Ruang Laboratorium</h5>
                                <p class="text-muted">Silakan tambahkan ruang laboratorium terlebih dahulu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('styles')
<style>
    .lab-card {
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        transition: all 0.3s ease;
        min-height: 280px;
    }

    .lab-card:hover {
        border-color: #4e73df;
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .lab-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lab-icon i {
        color: white !important;
    }

    .lab-info {
        max-width: 250px;
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .card-title {
        font-weight: 600;
        color: #5a5c69;
    }

    .lab-actions .btn {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .lab-card {
            min-height: 250px;
        }

        .lab-icon {
            width: 60px;
            height: 60px;
        }

        .lab-icon i {
            font-size: 2rem !important;
        }
    }
</style>
@endpush

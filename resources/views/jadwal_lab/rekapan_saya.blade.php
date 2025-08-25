@extends("layout.app")

@section("judul", "Rekapan Jadwal Lab Saya")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Jadwal Lab /</span> Rekapan Saya</h4>
        @include("komponen.alert")

        <!-- Statistik Card -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Aksi</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $totalAksi }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="bx bx-list-ul bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Tambah Jadwal</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $statistikAksi["tambah"] ?? 0 }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="bx bx-plus bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Edit Jadwal</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $statistikAksi["edit"] ?? 0 }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="bx bx-edit bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Ubah Status</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $statistikAksi["ubah_status"] ?? 0 }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="bx bx-refresh bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Export -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Filter & Export</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route("jadwalLab.rekapanSaya") }}" class="row g-3" id="filterForm">
                    <div class="col-md-4">
                        <label for="minggu_mulai" class="form-label">Filter Mingguan</label>
                        <input type="date" class="form-control" id="minggu_mulai" name="minggu_mulai"
                            value="{{ request("minggu_mulai") }}">
                        <small class="text-muted">Pilih tanggal awal minggu</small>
                    </div>
                    <div class="col-md-8 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-search me-1"></i>Filter
                        </button>
                        <a href="{{ route("jadwalLab.rekapanSaya") }}" class="btn btn-secondary me-2">
                            <i class="bx bx-refresh me-1"></i>Reset
                        </a>
                        <button type="button" class="btn btn-danger" onclick="exportPDF()">
                            <i class="bx bx-download me-1"></i>Export PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Rekapan -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Aktivitas Jadwal Lab</h5>
                <small class="text-muted">Total: {{ $totalAksi }} aktivitas</small>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table class="table table-striped" id="datatables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Waktu</th>
                            <th>Aksi</th>
                            <th>Ruang Lab</th>
                            <th>Jadwal</th>
                            <th>Dosen</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($dataRekapan as $index => $rekapan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <strong>{{ \Carbon\Carbon::parse($rekapan->tanggal_aksi)->locale("id")->translatedFormat("d M Y") }}</strong>
                                        <br>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($rekapan->waktu_aksi)->format("H:i:s") }}</small>
                                    </div>
                                </td>
                                <td>
                                    @switch($rekapan->aksi)
                                        @case("tambah")
                                            <span class="badge bg-success">
                                                <i class="bx bx-plus me-1"></i>Tambah
                                            </span>
                                        @break

                                        @case("edit")
                                            <span class="badge bg-warning">
                                                <i class="bx bx-edit me-1"></i>Edit
                                            </span>
                                        @break

                                        @case("hapus")
                                            <span class="badge bg-danger">
                                                <i class="bx bx-trash me-1"></i>Hapus
                                            </span>
                                        @break

                                        @case("ubah_status")
                                            <span class="badge bg-info">
                                                <i class="bx bx-refresh me-1"></i>Ubah Status
                                            </span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($rekapan->aksi) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if ($rekapan->jadwalLab && $rekapan->jadwalLab->ruangLaboratorium)
                                        <strong>{{ $rekapan->jadwalLab->ruangLaboratorium->nama_ruang }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($rekapan->jadwalLab)
                                        <div>
                                            <strong>{{ ucfirst($rekapan->jadwalLab->hari) }}</strong>
                                            <br>
                                            <small>{{ $rekapan->jadwalLab->waktu_mulai }} -
                                                {{ $rekapan->jadwalLab->waktu_selesai }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Data dihapus</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($rekapan->jadwalLab && $rekapan->jadwalLab->dosen)
                                        {{ $rekapan->jadwalLab->dosen->nama_dosen }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($rekapan->keterangan)
                                        <small>{{ $rekapan->keterangan }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push("scripts")
        <script>
            function exportPDF() {
                const form = document.getElementById('filterForm');
                const mingguMulai = document.getElementById('minggu_mulai').value;

                let url = '{{ route("jadwalLab.exportRekapanPdf") }}';
                if (mingguMulai) {
                    url += '?minggu_mulai=' + mingguMulai;
                }

                window.open(url, '_blank');
            }

            // Set default minggu ini jika belum ada filter
            document.addEventListener('DOMContentLoaded', function() {
                const mingguInput = document.getElementById('minggu_mulai');
                if (!mingguInput.value) {
                    // Set ke awal minggu ini (Senin)
                    const today = new Date();
                    const monday = new Date(today.setDate(today.getDate() - today.getDay() + 1));
                    mingguInput.value = monday.toISOString().split('T')[0];
                }
            });
        </script>
    @endpush

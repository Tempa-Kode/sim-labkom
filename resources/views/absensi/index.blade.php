@extends("layout.app")

@section("judul", "Data Absensi Aslab")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Absensi Aslab</h4>
        @include("komponen.alert")
        <div class="card">
            <h5 class="card-header">Daftar Absensi Aslab</h5>
            <div class="w-100 ps-4">
                <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                    <i class="fa-solid fa-file-pdf me-2"></i>PDF Data Absensi
                </button>
                <form id="filter-tanggal" action="" method="get">
                    <div class="d-flex align-items-end gap-2 mb-2">
                        <div>
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                                value="{{ request("tanggal_mulai") }}">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                                value="{{ request("tanggal_akhir") }}">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary mt-4">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Aslab</th>
                            <th>Hari</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $absensi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $absensi->aslab->nama }}</td>
                                <td>{{ $absensi->hari }}</td>
                                <td>{{ $absensi->tanggal }}</td>
                                <td>{{ $absensi->keterangan ?? "-" }}</td>
                                <td>
                                    <form action="{{ route("absensi.hapus", $absensi->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Export PDF --}}
    <div class="modal fade" id="exportPdfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export PDF Data Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="exportPdfForm" action="{{ route("absensi.exportPdf") }}" method="get" target="_blank">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Periode Export</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periode_type" id="semua_data"
                                    value="semua" checked>
                                <label class="form-check-label" for="semua_data">
                                    Semua Data
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periode_type" id="tanggal_tertentu"
                                    value="custom">
                                <label class="form-check-label" for="tanggal_tertentu">
                                    Tanggal Tertentu
                                </label>
                            </div>
                        </div>

                        <div id="tanggal_fields" style="display: none;">
                            <div class="mb-3" id="single_date_field">
                                <label for="export_tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="export_tanggal" name="tanggal">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-file-pdf me-2"></i>Export PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal Export PDF --}}
@endsection

@push("scripts")
    <script>
        $('#tanggal').on('change', function() {
            $('#filter-tanggal').submit();
        });

        // Handle radio button changes for export modal
        $('input[name="periode_type"]').on('change', function() {
            const value = $(this).val();
            const tanggalFields = $('#tanggal_fields');
            const singleDateField = $('#single_date_field');
            const rangeDateFields = $('#range_date_fields');

            if (value === 'semua') {
                tanggalFields.hide();
                // Clear all date inputs
                $('#export_tanggal, #export_tanggal_mulai, #export_tanggal_akhir').val('');
            } else if (value === 'custom') {
                tanggalFields.show();
                singleDateField.show();
                rangeDateFields.hide();
                // Clear range inputs
                $('#export_tanggal_mulai, #export_tanggal_akhir').val('');
            } else if (value === 'range') {
                tanggalFields.show();
                singleDateField.hide();
                rangeDateFields.show();
                // Clear single date input
                $('#export_tanggal').val('');
            }
        });

        // Handle form submission with validation
        $('#exportPdfForm').on('submit', function(e) {
            const periodeType = $('input[name="periode_type"]:checked').val();

            if (periodeType === 'custom') {
                const tanggal = $('#export_tanggal').val();
                if (!tanggal) {
                    e.preventDefault();
                    alert('Silakan pilih tanggal terlebih dahulu.');
                    return false;
                }
            } else if (periodeType === 'range') {
                const tanggalMulai = $('#export_tanggal_mulai').val();
                const tanggalAkhir = $('#export_tanggal_akhir').val();

                if (!tanggalMulai || !tanggalAkhir) {
                    e.preventDefault();
                    alert('Silakan pilih tanggal mulai dan tanggal akhir.');
                    return false;
                }

                if (tanggalMulai > tanggalAkhir) {
                    e.preventDefault();
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
                    return false;
                }
            }

            // Close modal on successful submission
            setTimeout(function() {
                $('#exportPdfModal').modal('hide');
            }, 500);
        });

        // Reset form when modal is closed
        $('#exportPdfModal').on('hidden.bs.modal', function() {
            $('#exportPdfForm')[0].reset();
            $('#tanggal_fields').hide();
            $('#semua_data').prop('checked', true);
        });
    </script>
@endpush

@extends("layout.app")

@section("judul", "Jadwal Ruang Lab")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Jadwal Ruang Lab</h4>
        @include("komponen.alert")
        <div class="card">
            <h5 class="card-header">Jadwal Penggunaan Ruang Laboratorium</h5>
            <div class="px-3 mb-3">
                @if (Auth::user()->hak_akses == "aslab")
                    <a href="{{ route("jadwalLab.tambah") }}" class="btn btn-primary">
                        <i class="fa-solid fa-square-plus me-2"></i>Tambah Jadwal
                    </a>
                @endif
                <div class="d-flex mt-3">
                    <a href="{{ route("jadwalLab.exportExcel") }}" class="btn btn-success me-3">
                        <i class="fa-solid fa-file-excel me-2"></i>Excel
                    </a>
                    <a href="{{ route("jadwalLab.exportPdf") }}" class="btn btn-danger">
                        <i class="fa-solid fa-file-pdf me-2"></i>PDF
                    </a>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        @php
                            $dowMap = [1=>'senin',2=>'selasa',3=>'rabu',4=>'kamis',5=>'jumat',6=>'sabtu',7=>'minggu'];
                            $todayDow = \Carbon\Carbon::now()->dayOfWeekIso; // 1=Mon .. 7=Sun
                            $todayName = $dowMap[$todayDow];
                        @endphp
                        <select class="form-select" name="hari" id="hari" data-default-hari="{{ $todayName }}">
                            <option value="senin">Senin</option>
                            <option value="selasa">Selasa</option>
                            <option value="rabu">Rabu</option>
                            <option value="kamis">Kamis</option>
                            <option value="jumat">Jumat</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruang</th>
                            <th>Hari</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Nama Dosen</th>
                            <th>Status</th>
                            <th>Sisa Waktu</th>
                            @if (Auth::user()->hak_akses == "aslab")
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $jadwal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jadwal->ruangLaboratorium->nama_ruang }}</td>
                                <td>{{ $jadwal->hari ?? "-" }}</td>
                                <td>{{ $jadwal->waktu_mulai ?? "-" }}</td>
                                <td>{{ $jadwal->waktu_selesai ?? "-" }}</td>
                                <td>{{ $jadwal->dosen->nama_dosen ?? "-" }}</td>
                                <td>
                                    @switch($jadwal->status_ruang)
                                        @case("digunakan")
                                            <button type="button"
                                                class="btn btn-info btn-sm text-uppercase">{{ $jadwal->status_ruang }}</button>
                                        @break

                                        @default
                                            <button type="button"
                                                class="btn btn-warning btn-sm text-uppercase">{{ $jadwal->status_ruang }}</button>
                                    @endswitch
                                </td>
                                <td>
                                    <span class="badge bg-secondary" data-countdown
                                        data-id="{{ $jadwal->id }}"
                                        data-hari="{{ strtolower($jadwal->hari) }}"
                                        data-mulai="{{ $jadwal->waktu_mulai }}"
                                        data-selesai="{{ $jadwal->waktu_selesai }}"
                                        data-status="{{ strtolower($jadwal->status_ruang) }}"></span>
                                </td>
                                @if (Auth::user()->hak_akses == "aslab")
                                    <td>
                                        <a href="{{ route("jadwalLab.edit", $jadwal->id) }}"
                                            class="btn btn-success btn-sm me-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route("jadwalLab.hapus", $jadwal->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route("jadwalLab.ubahStatus", $jadwal->id) }}" method="POST"
                                            class="d-inline ms-2">
                                            @csrf
                                            <select name="status_ruang" class="form-select form-select-sm d-inline-block"
                                                style="width: auto;">
                                                <option value="digunakan"
                                                    {{ $jadwal->status_ruang === "digunakan" ? "selected" : "" }}>digunakan
                                                </option>
                                                <option value="kosong"
                                                    {{ $jadwal->status_ruang === "kosong" ? "selected" : "" }}>kosong</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script type="text/javascript">
        var table = $('#datatables').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        // Filter berdasarkan hari
        $('#hari').on('change', function() {
            var selectedDay = this.value;

            if (selectedDay === '') {
                // Jika "Semua Hari" dipilih, tampilkan semua data
                table.column(2).search('').draw();
            } else {
                // Filter berdasarkan hari yang dipilih (kolom ke-3, index 2)
                table.column(2).search('^' + selectedDay + '$', true, false).draw();
            }

            // Update nomor urut setelah filter
            table.on('draw', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            });
        });

        // Set default hari = hari ini (jika ada dalam opsi)
        const hariEl = document.getElementById('hari');
        const def = hariEl.getAttribute('data-default-hari');
        if ([...hariEl.options].some(o => o.value === def)) {
            hariEl.value = def;
            $('#hari').trigger('change');
        }

        // Countdown realtime per baris
        function formatHMS(sec) {
            const s = Math.max(0, sec | 0);
            const h = Math.floor(s / 3600).toString().padStart(2, '0');
            const m = Math.floor((s % 3600) / 60).toString().padStart(2, '0');
            const ss = Math.floor(s % 60).toString().padStart(2, '0');
            return h + ":" + m + ":" + ss;
        }
        let timers = [];
        function clearTimers(){
            timers.forEach(t => clearInterval(t));
            timers = [];
        }
        function initCountdown(){
            clearTimers();
            const todayName = (function(){
                const map = {1:'senin',2:'selasa',3:'rabu',4:'kamis',5:'jumat',6:'sabtu',7:'minggu'};
                const d = new Date();
                // JS: 1=Mon..7=Sun
                const iso = (d.getDay() === 0) ? 7 : d.getDay();
                return map[iso];
            })();
            const toSec = (hhmm) => {
                if (!hhmm) return null;
                const [hStr,mStr] = hhmm.split(':');
                let h = parseInt(hStr,10), m = parseInt(mStr,10)||0;
                if (h === 24) return 24*3600; // 24:00 as end of day
                if (isNaN(h) || isNaN(m)) return null;
                return h*3600 + m*60;
            };
            const nowSec = () => {
                const n = new Date();
                return n.getHours()*3600 + n.getMinutes()*60 + n.getSeconds();
            };

            document.querySelectorAll('[data-countdown]').forEach(el => {
                const hari = (el.getAttribute('data-hari')||'').toLowerCase();
                const status = (el.getAttribute('data-status')||'').toLowerCase();
                const mulai = toSec(el.getAttribute('data-mulai'));
                const selesai = toSec(el.getAttribute('data-selesai'));
                const id = el.getAttribute('data-id');

                // Default tampil '-'
                el.textContent = '-';
                if (!hari || !status || mulai === null || selesai === null) return;
                if (hari !== todayName) return;
                if (status !== 'digunakan') return;

                let remain = Math.max(0, selesai - nowSec());
                if (remain <= 0 || nowSec() < mulai) {
                    // belum mulai atau sudah lewat
                    return;
                }

                el.textContent = formatHMS(remain);
                const t = setInterval(() => {
                    remain -= 1;
                    if (remain <= 0) {
                        el.textContent = 'Selesai';
                        clearInterval(t);
                        if (!el.dataset.done) {
                            el.dataset.done = '1';
                            fetch(`{{ url('/dashboard/jadwal-lab') }}/${id}/notify-selesai`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            }).then(r=>r.json()).then(resp=>{
                                // Update status badge in the same row to KOSONG
                                const row = el.closest('tr');
                                const statusBtn = row ? row.querySelector('td:nth-child(7) .btn') : null;
                                if (statusBtn) {
                                    statusBtn.textContent = (resp && resp.status) ? resp.status : 'kosong';
                                    statusBtn.classList.remove('btn-info');
                                    statusBtn.classList.add('btn-warning');
                                }
                            }).catch(()=>{});
                        }
                    } else {
                        el.textContent = formatHMS(remain);
                    }
                }, 1000);
                timers.push(t);
            });
        }

        // Initialize once and on each draw (filter/sort/paginate)
        initCountdown();
        table.on('draw', function(){
            initCountdown();
        });
    </script>
@endpush

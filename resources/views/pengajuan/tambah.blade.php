@extends("layout.app")

@section("judul", "Pengajuan")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home / </span> Pengajuan </h4>
        @include("komponen.alert")
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h5 class="mb-0 text-uppercase">Pengajuan Penggunaan Ruang Lab</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengajuan.simpan') }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jam_kode">Jam Kuliah</label>
                                <div class="col-sm-10">
                                    <select id="jam_kode" name="jam_kode" class="form-select @error('jam_kode') is-invalid @enderror">
                                        <option value="" hidden>Pilih Jam</option>
                                        @php
                                            use App\Helpers\JamHelper;
                                            $allJam = JamHelper::getAllJam();
                                            $tanggalPemakaian = old('tanggal_pemakaian', date('Y-m-d'));
                                            $isToday = $tanggalPemakaian === date('Y-m-d');
                                            $jamSekarang = date('H:i');
                                        @endphp
                                        @foreach ($allJam as $jam)
                                            @php
                                                $isDisabled = $isToday && $jam['waktu_selesai'] <= $jamSekarang;
                                            @endphp
                                            <option value="{{ $jam['kode'] }}"
                                                data-mulai="{{ $jam['waktu_mulai'] }}"
                                                data-selesai="{{ $jam['waktu_selesai'] }}"
                                                title="{{ $jam['waktu_mulai'] }} - {{ $jam['waktu_selesai'] }}{{ $isDisabled ? ' (Sudah Lewat)' : '' }}"
                                                @if (old('jam_kode') == $jam['kode']) selected @endif
                                                @if ($isDisabled) disabled style="color: #999; background-color: #f8f9fa;" @endif>
                                                {{ $jam['label'] }}{{ $isDisabled ? ' (Sudah Lewat)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <small id="jam-info" class="text-muted">Pilih jam untuk melihat waktu</small>
                                    </div>
                                    <input type="hidden" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}">
                                    <input type="hidden" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}">
                                </div>
                            </div>
                            {{-- <div class="row mb-3 ">
                                <label class="col-sm-2 col-form-label" for="tanggal_pengajuan">Tanggal Pengajuan</label>
                                <div class="col-sm-10"> --}}
                                    <input hidden class="form-control @error("tanggal_pengajuan") is-invalid @enderror"
                                        type="date" id="tanggal_pengajuan" name="tanggal_pengajuan"
                                        value="{{ date("Y-m-d") }}" readonly />
                                {{-- </div>
                            </div> --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="tanggal_pemakaian">Tanggal Pemakaian</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("tanggal_pemakaian") is-invalid @enderror"
                                        type="date" id="tanggal_pemakaian" name="tanggal_pemakaian"
                                        value="{{ old("tanggal_pemakaian", date("Y-m-d")) }}" readonly/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="hari">Hari</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("hari") is-invalid @enderror"
                                        type="text" id="hari" name="hari"
                                        value="{{ old('hari', \Carbon\Carbon::now()->locale('id')->isoFormat('dddd')) }}" readonly/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_ruang">Ruang Lab</label>
                                <div class="col-sm-10">
                                    <select id="id_ruang" name="id_ruang"
                                        class="form-select @error("id_ruang") is-invalid @enderror">
                                        <option value="" hidden>Pilih Ruang Lab</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_dosen">Nama Dosen</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text"
                                        id="id_dosen" name="id_dosen" value="{{ Auth::user()->dosen->id }}" hidden />
                                    <input class="form-control @error("dosen") is-invalid @enderror" type="text"
                                        id="dosen" name="dosen" value="{{ Auth::user()->nama }}" readonly />
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Di Ajukan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Update dropdown jam berdasarkan tanggal yang dipilih
    function updateJamDropdown() {
        const tanggalPemakaian = document.getElementById('tanggal_pemakaian').value;
        const jamSelect = document.getElementById('jam_kode');
        const today = new Date().toISOString().split('T')[0];
        const isToday = tanggalPemakaian === today;
        
        if (isToday) {
            const now = new Date();
            const jamSekarang = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            // Update semua option berdasarkan waktu sekarang
            Array.from(jamSelect.options).forEach(option => {
                if (option.value) {
                    const jamSelesai = option.getAttribute('data-selesai');
                    const isExpired = jamSelesai <= jamSekarang;
                    
                    option.disabled = isExpired;
                    if (isExpired) {
                        option.style.color = '#999';
                        option.style.backgroundColor = '#f8f9fa';
                        option.textContent = option.textContent.replace(' (Sudah Lewat)', '') + ' (Sudah Lewat)';
                        option.title = option.title.replace(' (Sudah Lewat)', '') + ' (Sudah Lewat)';
                        
                        // Reset pilihan jika yang dipilih sudah expired
                        if (option.selected) {
                            jamSelect.value = '';
                            document.getElementById('jam_mulai').value = '';
                            document.getElementById('jam_selesai').value = '';
                            document.getElementById('jam-info').innerHTML = 'Pilih jam untuk melihat waktu';
                            document.getElementById('jam-info').className = 'text-muted';
                        }
                    } else {
                        option.disabled = false;
                        option.style.color = '';
                        option.style.backgroundColor = '';
                        option.textContent = option.textContent.replace(' (Sudah Lewat)', '');
                        option.title = option.title.replace(' (Sudah Lewat)', '');
                    }
                }
            });
        } else {
            // Jika bukan hari ini, aktifkan semua jam
            Array.from(jamSelect.options).forEach(option => {
                if (option.value) {
                    option.disabled = false;
                    option.style.color = '';
                    option.style.backgroundColor = '';
                    option.textContent = option.textContent.replace(' (Sudah Lewat)', '');
                    option.title = option.title.replace(' (Sudah Lewat)', '');
                }
            });
        }
    }

    // Auto-fill waktu berdasarkan pilihan jam
    document.getElementById('jam_kode').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const jamInfo = document.getElementById('jam-info');

        if (selectedOption.value && !selectedOption.disabled) {
            const mulai = selectedOption.getAttribute('data-mulai');
            const selesai = selectedOption.getAttribute('data-selesai');

            document.getElementById('jam_mulai').value = mulai;
            document.getElementById('jam_selesai').value = selesai;

            // Update info waktu
            jamInfo.innerHTML = `<strong>${selectedOption.text.replace(' (Sudah Lewat)', '')}</strong>: ${mulai} - ${selesai}`;
            jamInfo.className = 'text-success';

            // Trigger validasi waktu dan load ruang setelah jam diubah
            validateTime();
            loadRuangTersedia();
        } else {
            document.getElementById('jam_mulai').value = '';
            document.getElementById('jam_selesai').value = '';
            jamInfo.innerHTML = 'Pilih jam untuk melihat waktu';
            jamInfo.className = 'text-muted';
        }
    });    // Hindari duplikasi opsi: debounce, abort request lama, ignore stale responses, dan clear sebelum load
    let ruangReqSeq = 0;            // sequence id untuk tiap request
    let ruangActiveSeq = 0;         // seq yang terakhir diproses
    let ruangAbortCtrl = null;      // AbortController untuk batalkan fetch sebelumnya
    let ruangDebounce = null;       // timer debounce

    function clearOptions(selectEl, placeholder = 'Pilih Ruang Lab') {
        selectEl.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.hidden = true;
        opt.textContent = placeholder;
        selectEl.appendChild(opt);
    }

    async function loadRuangTersediaInternal(seq) {
        const hari = document.getElementById('hari').value?.toLowerCase();
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;
        const select = document.getElementById('id_ruang');

        // Clear opsi lama & tampilkan status memuat
        select.disabled = true;
        clearOptions(select, 'Memuat...');
        if (!hari || !jamMulai || !jamSelesai) {
            clearOptions(select, 'Lengkapi waktu & hari');
            select.disabled = false;
            return;
        }

        try {
            // Batalkan request sebelumnya bila ada
            if (ruangAbortCtrl) {
                try { ruangAbortCtrl.abort(); } catch (_) {}
            }
            ruangAbortCtrl = new AbortController();
            const params = new URLSearchParams({ hari, jam_mulai: jamMulai, jam_selesai: jamSelesai });
            const res = await fetch(`{{ route('pengajuan.ruangTersedia') }}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: ruangAbortCtrl.signal
            });
            const json = await res.json();
            // Ignore jika ini response lama (stale)
            if (seq < ruangActiveSeq) return;
            ruangActiveSeq = seq;

            // Tulis ulang opsi secara bersih
            clearOptions(select);
            const seen = new Set();
            if (json && json.success && Array.isArray(json.data)) {
                json.data.forEach(r => {
                    if (!r || seen.has(r.id)) return;
                    seen.add(r.id);
                    const opt = document.createElement('option');
                    opt.value = r.id;
                    opt.textContent = r.nama_ruang;
                    select.appendChild(opt);
                });
            }
            if (seen.size === 0) {
                clearOptions(select, 'Tidak ada ruang tersedia');
            }
        } catch (e) {
            if (e.name !== 'AbortError') {
                console.error(e);
                clearOptions(document.getElementById('id_ruang'), 'Gagal memuat');
            }
        } finally {
            select.disabled = false;
        }
    }

    function loadRuangTersedia() {
        // debounce 300ms untuk mengurangi request berulang saat mengetik
        if (ruangDebounce) clearTimeout(ruangDebounce);
        ruangDebounce = setTimeout(() => {
            ruangReqSeq += 1;
            loadRuangTersediaInternal(ruangReqSeq);
        }, 300);
    }

    // Validasi waktu real-time
    function validateTime() {
        const tanggalPemakaian = document.getElementById('tanggal_pemakaian').value;
        const jamSelesai = document.getElementById('jam_selesai').value;

        if (tanggalPemakaian && jamSelesai) {
            const sekarang = new Date();
            const tanggalWaktuSelesai = new Date(tanggalPemakaian + 'T' + jamSelesai);

            // Cek apakah waktu selesai yang dipilih sudah lewat
            if (tanggalWaktuSelesai < sekarang) {
                document.getElementById('jam_kode').setCustomValidity('Tidak dapat mengajukan penggunaan lab pada jam yang sudah lewat');
                return false;
            } else {
                document.getElementById('jam_kode').setCustomValidity('');
                return true;
            }
        }
        document.getElementById('jam_kode').setCustomValidity('');
        return true;
    }

    // Update pilihan hari saat tanggal pemakaian berubah (agar konsisten dengan bahasa)
    document.getElementById('tanggal_pemakaian').addEventListener('change', function() {
        updateJamDropdown();
        validateTime();
        loadRuangTersedia();
    });

    document.getElementById('jam_mulai').addEventListener('change', function() {
        validateTime();
    });

    document.getElementById('jam_selesai').addEventListener('change', function() {
        validateTime();
    });

    document.getElementById('jam_kode').addEventListener('change', function() {
        validateTime();
    });

    ['hari','jam_kode'].forEach(id => {
        const el = document.getElementById(id);
        el.addEventListener('change', loadRuangTersedia);
        el.addEventListener('input', loadRuangTersedia);
    });

    // Validasi sebelum submit form
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateTime()) {
            e.preventDefault();
            alert('Tidak dapat mengajukan penggunaan lab pada jam yang sudah lewat. Silakan pilih jam yang akan datang.');
            return false;
        }
    });

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        updateJamDropdown();
        loadRuangTersedia();
    });
</script>
@endpush

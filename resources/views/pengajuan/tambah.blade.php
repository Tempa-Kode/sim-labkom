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
                                <label class="col-sm-2 col-form-label" for="jam_mulai">Waktu Mulai</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("jam_mulai") is-invalid @enderror" type="time"
                                        id="jam_mulai" name="jam_mulai" value="{{ old("jam_mulai") }}"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jam_selesai">Waktu Selesai</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("jam_selesai") is-invalid @enderror" type="time"
                                        id="jam_selesai" name="jam_selesai"
                                        value="{{ old("jam_selesai") }}"/>
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
    // Hindari duplikasi opsi: debounce, abort request lama, ignore stale responses, dan clear sebelum load
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

    // Update pilihan hari saat tanggal pemakaian berubah (agar konsisten dengan bahasa)
    document.getElementById('tanggal_pemakaian').addEventListener('change', function() {
        // Biarkan user memilih manual jika perlu; tidak mengubah opsi otomatis agar sederhana
        // Namun tetap coba refresh ketersediaan ruang
        loadRuangTersedia();
    });
    ['hari','jam_mulai','jam_selesai'].forEach(id => {
        const el = document.getElementById(id);
        el.addEventListener('change', loadRuangTersedia);
        el.addEventListener('input', loadRuangTersedia);
    });

    // initial load in case fields pre-filled
    document.addEventListener('DOMContentLoaded', loadRuangTersedia);
</script>
@endpush

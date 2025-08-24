@extends('layout.app')

@section('judul', 'Edit Ruang Lab')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home / Jadwal Ruang Lab /</span> Edit</h4>
        @include('komponen.alert')
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h5 class="mb-0 text-uppercase">Form Edit Jadwal Penggunaan Ruang Laboratorium</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jadwalLab.update', $jadwal->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_ruang_lab">Ruang Lab</label>
                                <div class="col-sm-10">
                                    <select id="id_ruang_lab" name="id_ruang_lab" class="form-select @error('id_ruang_lab') is-invalid @enderror">
                                        <option value="" hidden>Pilih Ruang Lab</option>
                                        @foreach ($ruangLab as $ruang)
                                            <option value="{{ $ruang->id }}" @if (old('id_ruang_lab', $jadwal->id_ruang_lab) == $ruang->id) selected @endif>{{ $ruang->nama_ruang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="hari">Hari</label>
                                <div class="col-sm-10">
                                    <select id="hari" name="hari" class="form-select @error('hari') is-invalid @enderror">
                                        <option value="" hidden>Pilih Hari</option>
                                        <option value="senin" @if (old('hari', $jadwal->hari) == 'senin') selected @endif>Senin</option>
                                        <option value="selasa" @if (old('hari', $jadwal->hari) == 'selasa') selected @endif>Selasa</option>
                                        <option value="rabu" @if (old('hari', $jadwal->hari) == 'rabu') selected @endif>Rabu</option>
                                        <option value="kamis" @if (old('hari', $jadwal->hari) == 'kamis') selected @endif>Kamis</option>
                                        <option value="jumat" @if (old('hari', $jadwal->hari) == 'jumat') selected @endif>Jumat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jam_kode">Jam Kuliah</label>
                                <div class="col-sm-10">
                                    <select id="jam_kode" name="jam_kode" class="form-select @error('jam_kode') is-invalid @enderror">
                                        <option value="" hidden>Pilih Jam</option>
                                        @php
                                            use App\Helpers\JamHelper;
                                            $allJam = JamHelper::getAllJam();
                                            $currentJam = JamHelper::waktuKeJam($jadwal->waktu_mulai, $jadwal->waktu_selesai);
                                            $currentKode = str_replace('Jam ', '', $currentJam);
                                            $hariSekarang = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
                                            $jamSekarang = date('H:i');
                                        @endphp
                                        @foreach ($allJam as $jam)
                                            @php
                                                $selectedHari = old('hari', $jadwal->hari);
                                                $isToday = $selectedHari === $hariSekarang;
                                                $isDisabled = $isToday && $jam['waktu_selesai'] <= $jamSekarang;
                                            @endphp
                                            <option value="{{ $jam['kode'] }}"
                                                data-mulai="{{ $jam['waktu_mulai'] }}"
                                                data-selesai="{{ $jam['waktu_selesai'] }}"
                                                title="{{ $jam['waktu_mulai'] }} - {{ $jam['waktu_selesai'] }}{{ $isDisabled ? ' (Sudah Lewat)' : '' }}"
                                                @if (old('jam_kode', $currentKode) == $jam['kode']) selected @endif
                                                @if ($isDisabled) disabled style="color: #999; background-color: #f8f9fa;" @endif>
                                                {{ $jam['label'] }}{{ $isDisabled ? ' (Sudah Lewat)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        <small id="jam-info" class="text-success">
                                            <strong>{{ $currentJam }}</strong>: {{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }}
                                        </small>
                                    </div>
                                    <input type="hidden" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') ? date('H:i', strtotime(old('waktu_mulai'))) : date('H:i', strtotime($jadwal->waktu_mulai)) }}">
                                    <input type="hidden" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') ? date('H:i', strtotime(old('waktu_selesai'))) : date('H:i', strtotime($jadwal->waktu_selesai)) }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_dosen">Nama Dosen</label>
                                <div class="col-sm-10">
                                    <select id="id_dosen" name="id_dosen" class="form-select @error('id_dosen') is-invalid @enderror">
                                        <option value="" hidden>Pilih Dosen</option>
                                        @foreach ($dosen as $dsn)
                                            <option value="{{ $dsn->id }}" @if (old('id_dosen', $jadwal->id_dosen) == $dsn->id) selected @endif>{{ $dsn->nama_dosen }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="status_ruang">Status</label>
                                <div class="col-sm-10">
                                    <select id="status_ruang" name="status_ruang" class="form-select @error('status_ruang') is-invalid @enderror">
                                        <option value="" hidden>Pilih Status</option>
                                        <option value="digunakan" @if(old('status_ruang', $jadwal->status_ruang) == 'digunakan') selected @endif>Digunakan</option>
                                        <option value="kosong" @if(old('status_ruang', $jadwal->status_ruang) == 'kosong') selected @endif>Kosong</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="alasan-kosong-field" style="display: none;">
                                <label class="col-sm-2 col-form-label" for="alasan_kosong">Alasan Kosong</label>
                                <div class="col-sm-10">
                                    <textarea id="alasan_kosong" name="alasan_kosong" class="form-control @error('alasan_kosong') is-invalid @enderror"
                                        rows="3" maxlength="500" placeholder="Contoh: Dosen sakit, ada urusan mendadak, jam sudah lewat, libur nasional, maintenance ruang, dll.">{{ old('alasan_kosong', $jadwal->alasan_kosong) }}</textarea>
                                    <div class="form-text">
                                        <small class="text-muted">Hanya wajib diisi jika status ruang "Kosong". Jelaskan alasan mengapa ruang kosong.</small>
                                        <small id="char-count-edit" class="text-muted float-end">{{ strlen(old('alasan_kosong', $jadwal->alasan_kosong ?? '')) }}/500 karakter</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update dropdown jam berdasarkan hari yang dipilih
        function updateJamDropdown() {
            const hariSelect = document.getElementById('hari');
            const jamSelect = document.getElementById('jam_kode');
            const selectedHari = hariSelect.value;

            const now = new Date();
            const hariSekarang = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'][now.getDay()];
            const jamSekarang = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            const isToday = selectedHari === hariSekarang;

            if (isToday) {
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

                document.getElementById('waktu_mulai').value = mulai;
                document.getElementById('waktu_selesai').value = selesai;

                // Update info waktu
                jamInfo.innerHTML = `<strong>${selectedOption.text.replace(' (Sudah Lewat)', '')}</strong>: ${mulai} - ${selesai}`;
                jamInfo.className = 'text-success';

                // Validasi waktu jika hari yang dipilih adalah hari ini
                validateScheduleTime();
            } else {
                document.getElementById('waktu_mulai').value = '';
                document.getElementById('waktu_selesai').value = '';
                jamInfo.innerHTML = 'Pilih jam untuk melihat waktu';
                jamInfo.className = 'text-muted';
            }
        });        // Validasi jadwal waktu
        function validateScheduleTime() {
            const hari = document.getElementById('hari').value;
            const waktuSelesai = document.getElementById('waktu_selesai').value;

            if (hari && waktuSelesai) {
                const sekarang = new Date();
                const hariSekarang = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'][sekarang.getDay()];

                // Jika hari yang dipilih adalah hari ini
                if (hari === hariSekarang) {
                    const jamSekarang = sekarang.getHours() + ':' + sekarang.getMinutes().toString().padStart(2, '0');

                    if (waktuSelesai < jamSekarang) {
                        document.getElementById('waktu_mulai').setCustomValidity('Tidak dapat mengubah jadwal pada jam yang sudah lewat untuk hari ini');
                        document.getElementById('waktu_mulai').reportValidity();
                        return false;
                    } else {
                        document.getElementById('waktu_mulai').setCustomValidity('');
                        return true;
                    }
                } else {
                    document.getElementById('waktu_mulai').setCustomValidity('');
                    return true;
                }
            }
            return true;
        }

        // Validasi saat hari berubah
        document.getElementById('hari').addEventListener('change', function() {
            updateJamDropdown();
            validateScheduleTime();
        });

        // Validasi sebelum submit form
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validateScheduleTime()) {
                e.preventDefault();
                alert('Tidak dapat mengubah jadwal pada jam yang sudah lewat untuk hari ini.');
                return false;
            }
        });

        // Validasi awal saat halaman dimuat (untuk edit)
        document.addEventListener('DOMContentLoaded', function() {
            updateJamDropdown();
            validateScheduleTime();
            toggleAlasanKosong(); // Check initial state
        });

        // Show/hide alasan kosong berdasarkan status ruang
        function toggleAlasanKosong() {
            const statusRuang = document.getElementById('status_ruang').value;
            const alasanKosongField = document.getElementById('alasan-kosong-field');

            if (statusRuang === 'kosong') {
                alasanKosongField.style.display = 'flex';
            } else {
                alasanKosongField.style.display = 'none';
                document.getElementById('alasan_kosong').value = ''; // Reset value
            }
        }

        // Event listener untuk status ruang
        document.getElementById('status_ruang').addEventListener('change', toggleAlasanKosong);

        // Character counter untuk textarea alasan kosong
        document.getElementById('alasan_kosong').addEventListener('input', function() {
            const textarea = this;
            const charCount = document.getElementById('char-count-edit');
            const currentLength = textarea.value.length;
            charCount.textContent = currentLength + '/500 karakter';

            // Ubah warna jika mendekati limit
            if (currentLength > 450) {
                charCount.className = 'text-danger';
            } else if (currentLength > 400) {
                charCount.className = 'text-warning';
            } else {
                charCount.className = 'text-muted';
            }
        });
    </script>
@endsection

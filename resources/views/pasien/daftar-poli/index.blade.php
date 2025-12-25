<x-layouts.app title="Daftar Poli">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Poli</h6>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                            @csrf
                            <div class="mb-5">
                                <label class="form-label">Pilih Jadwal Dokter</label>
                                <select name="id_jadwal" class="form-select @error('id_jadwal') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jadwal --</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}">
                                            {{ $jadwal->dokter->nama }} | Poli {{ $jadwal->dokter->poli->nama_poli }} 
                                            ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jadwal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Keluhan</label>
                                <textarea name="keluhan" class="form-control @error('keluhan') is-invalid @enderror" rows="3" placeholder="Masukkan keluhan Anda" required>{{ old('keluhan') }}</textarea>
                                @error('keluhan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Daftar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Riwayat Pendaftaran Poli</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Poli</th>
                                        <th>Dokter</th>
                                        <th>Hari</th>
                                        <th>Antrean</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatDaftar as $riwayat)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli }}</td>
                                            <td>{{ $riwayat->jadwalPeriksa->dokter->nama }}</td>
                                            <td>{{ $riwayat->jadwalPeriksa->hari }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark">{{ $riwayat->no_antrian }}</span>
                                            </td>
                                            <td>
                                                @if($riwayat->periksas->isNotEmpty())
                                                    <span class="badge bg-success">Sudah Diperiksa</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada riwayat pendaftaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
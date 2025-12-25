<x-layouts.app title="Riwayat Periksa">
    <div class="container-fluid px-4 mt-4">
        <h1 class="mb-4">Riwayat Periksa Saya</h1>
        <div class="row">
            @forelse($riwayatPeriksa as $rp)
                <div class="col-md-6 mb-4">
                    <div class="card shadow border-left-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Periksa Tanggal: {{ \Carbon\Carbon::parse($rp->tgl_periksa)->format('d M Y') }}
                            </h6>
                            <span class="badge bg-success text-white">Selesai</span>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="30%">Dokter</th>
                                    <td>: {{ $rp->daftarPoli->jadwalPeriksa->dokter->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Poli</th>
                                    <td>: {{ $rp->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli }}</td>
                                </tr>
                                <tr>
                                    <th>Keluhan</th>
                                    <td>: {{ $rp->daftarPoli->keluhan }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>: <span class="text-info">{{ $rp->catatan }}</span></td>
                                </tr>
                                <tr>
                                    <th>Obat</th>
                                    <td>: 
                                        @foreach($rp->detailPeriksas as $dp)
                                            <span class="badge bg-light text-dark border">{{ $dp->obat->nama_obat }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Biaya</th>
                                    <td>: <strong class="text-danger">Rp {{ number_format($rp->biaya_periksa, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Belum ada riwayat pemeriksaan medis.</div>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
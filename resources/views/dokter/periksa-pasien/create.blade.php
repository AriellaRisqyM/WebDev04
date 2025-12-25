<x-layouts.app title="Periksa Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Periksa Pasien</h1>

                {{-- Alert untuk pesan error dari Controller (Validasi Sisi Server) --}}
                @if(session('message'))
                    <div class="alert alert-{{ session('type') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Pemeriksaan</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('periksa.pasien.store') }}" method="POST" id="form-periksa">
                            @csrf
                            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                            <div class="form-group mb-3">
                                <label for="select-obat" class="form-label">Pilih Obat</label>
                                <select id="select-obat" class="form-select">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        {{-- PERBAIKAN: Menambahkan data-stok dan keterangan stok pada tampilan --}}
                                        <option value="{{ $obat->id }}" 
                                                data-nama="{{ $obat->nama_obat }}"
                                                data-harga="{{ $obat->harga }}"
                                                data-stok="{{ $obat->stok }}"
                                                {{ $obat->stok < 1 ? 'disabled' : '' }}>
                                            {{ $obat->nama_obat }} - Rp {{ number_format($obat->harga, 0, ',', '.') }} 
                                            ({{ $obat->stok < 1 ? 'STOK HABIS' : 'Sisa: ' . $obat->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted text-italic">*Obat yang stoknya habis tidak dapat dipilih.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan Medis</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="4" required placeholder="Masukkan catatan hasil pemeriksaan...">{{ old('catatan') }}</textarea>
                                @error('catatan') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="fw-bold">Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2 shadow-sm">
                                    {{-- Diisi oleh JavaScript --}}
                                </ul>
                                
                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Biaya Obat (+ Jasa Dokter Rp 150.000):</span>
                                <h4 id="total-harga" class="m-0 fw-bold">Rp 150.000</h4>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success px-4" id="btn-simpan">
                                    <i class="fas fa-save me-1"></i> Simpan Pemeriksaan
                                </button>
                                <a href="{{ route('periksa.pasien.index') }}" class="btn btn-secondary px-4">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    const selectObat = document.getElementById('select-obat');
    const listObat = document.getElementById('obat-terpilih');
    const inputBiaya = document.getElementById('biaya_periksa');
    const inputObatJson = document.getElementById('obat_json');
    const totalHargaEl = document.getElementById('total-harga');

    let daftarObat = [];
    const JASA_DOKTER = 150000;

    selectObat.addEventListener('change', () => {
        const selectedOption = selectObat.options[selectObat.selectedIndex];
        const id = selectedOption.value;
        const nama = selectedOption.dataset.nama;
        const harga = parseInt(selectedOption.dataset.harga || 0);
        const stok = parseInt(selectedOption.dataset.stok || 0);

        // PERBAIKAN: Validasi stok habis di sisi Front-end 
        if (id && stok < 1) {
            alert('Maaf, stok obat "' + nama + '" sudah habis dan tidak bisa ditambahkan.');
            selectObat.selectedIndex = 0;
            return;
        }

        // Cegah duplikasi obat
        if (!id || daftarObat.some(o => o.id == id)) {
            selectObat.selectedIndex = 0;
            return;
        }

        daftarObat.push({ id, nama, harga });
        renderObat();
        selectObat.selectedIndex = 0;
    });

    function renderObat() {
        listObat.innerHTML = '';
        let totalObat = 0;

        daftarObat.forEach((obat, index) => {
            totalObat += obat.harga;

            const item = document.createElement('li');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';
            item.innerHTML = `
                <span><strong>${obat.nama}</strong> - Rp ${obat.harga.toLocaleString('id-ID')}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusObat(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            listObat.appendChild(item);
        });

        // Update total (Biaya Obat + Jasa Dokter)
        const totalAkhir = totalObat + JASA_DOKTER;
        inputBiaya.value = totalObat; // Controller akan menambahkan 150.000 lagi, atau sesuaikan logika simpan Anda
        totalHargaEl.textContent = `Rp ${totalAkhir.toLocaleString('id-ID')}`;
        
        inputObatJson.value = JSON.stringify(daftarObat.map(o => ({ id: o.id, jumlah: 1 })));
    }

    function hapusObat(index) {
        daftarObat.splice(index, 1);
        renderObat();
    }
</script>
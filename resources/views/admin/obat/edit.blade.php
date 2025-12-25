<x-layouts.app title="Edit Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">

                <h1 class="mb-4">Edit Obat</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Edit Data Obat</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('obat.update', $obat->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_obat" class="form-label">Nama Obat
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama_obat" id="nama_obat"
                                            class="form-control @error('nama_obat') is-invalid @enderror"
                                            value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                                        @error('nama_obat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kemasan" class="form-label">Kemasan</label>
                                        <input type="text" name="kemasan" id="kemasan"
                                            class="form-control @error('kemasan') is-invalid @enderror"
                                            value="{{ old('kemasan', $obat->kemasan) }}"
                                            placeholder="Contoh: Strip, Botol, Tube">
                                        @error('kemasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="harga" class="form-label">Harga (Rp)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="harga" id="harga"
                                            class="form-control @error('harga') is-invalid @enderror"
                                            value="{{ old('harga', $obat->harga) }}" required min="0" step="1">
                                        @error('harga')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- FITUR UAS: Input stok untuk manajemen manual oleh Admin --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="stok" class="form-label">Stok Saat Ini
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="stok" id="stok"
                                            class="form-control @error('stok') is-invalid @enderror"
                                            value="{{ old('stok', $obat->stok) }}" required min="0">
                                        @error('stok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4 text-end">
                                <a href="{{ route('obat.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
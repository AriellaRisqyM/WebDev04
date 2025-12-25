<x-layouts.app title="Admin Dashboard">
    <div class="container-fluid px-4 mt-4">
        <h1 class="mb-4">Halo, Selamat Datang Admin!</h1>

        {{-- BAGIAN SEDERHANA: Mengambil data langsung di Blade --}}
        @php
            // Mengambil obat yang stoknya di bawah 5
            $obatmenipis = \App\Models\Obat::where('stok', '<', 10)->get();
        @endphp

        {{-- Tampilkan Alert hanya jika ada obat yang stoknya menipis --}}
        @if($obatmenipis->count() > 0)
            <div class="alert alert-danger shadow-sm border-left-danger">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1 font-weight-bold">Peringatan Stok Obat!</h5>
                        <p class="mb-0">
                            Ada <strong>{{ $obatmenipis->count() }} obat</strong> yang hampir habis. 
                            <a href="{{ route('obat.index') }}" class="alert-link">Klik di sini untuk mengelola stok.</a>
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-success shadow-sm">
                <i class="fas fa-check-circle me-2"></i> Semua stok obat dalam kondisi aman.
            </div>
        @endif

        {{-- Konten Dashboard Lainnya --}}
        <div class="card mt-3">
            <div class="card-body">
                ...
            </div>
        </div>
    </div>
</x-layouts.app>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Minimarket - POS</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .pos-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .table-wrapper {
            min-height: 350px;
            max-height: 450px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <!-- Navbar Sederhana -->
    <!-- Navbar dengan Aksen Merah Putih -->
<nav class="navbar navbar-expand-lg navbar-light bg-white px-4 border-bottom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-dark" href="#">
            <span class="bg-danger text-white px-2 py-1 rounded me-2">
                <i class="fas fa-cash-register"></i>
            </span> 
            Kasir Minimarket
        </a>
        <div class="d-flex align-items-center">
            <span class="me-3 text-secondary small"><i class="fas fa-user-tie me-1"></i> Kasir: <strong>{{ $namaKasir }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm px-3"><i class="fas fa-sign-out-alt me-1"></i> Logout</button>
            </form>
        </div>
    </div>
</nav>

    <!-- Main Container -->
    <div class="container-fluid px-4 py-4">
        <div class="row g-4">
            
            <!-- KOLOM KIRI: Keranjang & Daftar Belanja -->
            <div class="col-lg-8">
                <div class="card pos-card h-100">
                    <div class="card-header bg-white py-3">
                        <div class="input-group">
                          <span class="input-group-text bg-danger text-white border-danger"><i class="fas fa-barcode"></i></span>
                          <input type="text" class="form-control form-control-lg border-secondary-subtle" placeholder="Scan Barcode atau Ketik Nama Produk di sini..." autofocus>
                          <button class="btn btn-primary px-4" type="button">Cari</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-wrapper">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="table-dark bg-dark text-white">
                                    <tr>
                                        <th class="ps-3">No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th style="width: 120px;">Qty</th>
                                        <th>Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Contoh Data Dummy Sementara (Nanti diisi dinamis dari database) -->
                                    <tr>
                                        <td class="ps-3">1</td>
                                        <td>Aqua 600ml</td>
                                        <td>Rp 4.000</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center" value="2" min="1">
                                        </td>
                                        <td>Rp 8.000</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-3">2</td>
                                        <td>Indomie Goreng Special</td>
                                        <td>Rp 3.500</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center" value="5" min="1">
                                        </td>
                                        <td>Rp 17.500</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-outline-danger"><i class="fas fa-trash-alt me-1"></i> Kosongkan Keranjang</button>
                            <span class="text-muted small">Total Item di Keranjang: <strong>7 pcs</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Panel Pembayaran & Total -->
            <div class="col-lg-4">
                <div class="card pos-card h-100 bg-white">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold text-dark mb-4 border-bottom pb-2">Ringkasan Pembayaran</h4>
                            
                           <!-- Total Tagihan Display Besar -->
                            <div class="bg-dark text-warning p-3 rounded text-end mb-4 shadow-sm border-start border-danger border-4">
                              <span class="d-block text-white-50 small text-uppercase">Total Tagihan</span>
                              <h1 class="fw-bold mb-0 text-warning">Rp 25.500</h1>
                            </div>

                            <!-- Input Pembayaran Tunai -->
                            <div class="mb-3">
                                <label for="cash" class="form-label fw-semibold">Jumlah Uang Tunai (Rp)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" id="cash" class="form-control fw-bold text-end" placeholder="0">
                                </div>
                            </div>

                            <!-- Uang Kembalian -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Uang Kembalian</label>
                                <div class="alert alert-success fs-4 fw-bold text-end mb-0 py-2" role="alert">
                                    Rp 0
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi Pembayaran -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-danger btn-lg py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPembayaran">
                             <i class="fas fa-check-circle me-2"></i> PROSES TRANSAKSI & CETAK
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-pause me-1"></i> Hold Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1" aria-labelledby="modalPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <!-- Header Modal -->
            <div class="modal-header bg-danger text-white border-0 py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="modalPembayaranLabel">Pembayaran</h5>
                    <small class="text-white-50">Pilih metode bayar</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body p-4 bg-white">
                
                <!-- Box Total Tagihan (Nilai aslinya disimpan di atribut data-total agar mudah dibaca script) -->
                <div class="text-center p-3 mb-4 rounded-3 border bg-light">
                    <span class="d-block text-muted small mb-1">Total Tagihan</span>
                    <h2 class="fw-bold text-danger mb-0" id="displayTotalTagihan" data-total="7500">Rp 7.500</h2>
                </div>

                <!-- Pilihan Metode Pembayaran -->
                <div class="mb-4">
                    <div class="nav nav-pills nav-fill bg-light p-1 rounded-3" role="tablist">
                        <button class="nav-link active bg-danger text-white fw-semibold rounded-3 py-2" data-bs-toggle="pill" data-bs-target="#content-tunai" type="button">
                            <i class="fas fa-wallet me-1"></i> Tunai
                        </button>
                        <button class="nav-link text-dark fw-semibold rounded-3 py-2" data-bs-toggle="pill" data-bs-target="#content-debit" type="button">
                            <i class="fas fa-credit-card me-1"></i> Debit
                        </button>
                        <button class="nav-link text-dark fw-semibold rounded-3 py-2" data-bs-toggle="pill" data-bs-target="#content-qris" type="button">
                            <i class="fas fa-qrcode me-1"></i> QRIS
                        </button>
                    </div>
                </div>

                <!-- Konten Pembayaran Tunai -->
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="content-tunai" role="tabpanel">
                        
                        <!-- Input Uang Diterima -->
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Uang Diterima</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light text-danger fw-bold border-danger">Rp</span>
                                <input type="text" id="inputUangDiterima" class="form-control fw-bold text-danger border-danger" placeholder="0" autocomplete="off">
                            </div>
                        </div>

                        <!-- Tombol Nominal Cepat (Quick Cash) -->
                        <div class="row g-2 mb-4">
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100 btn-sm py-2 fw-semibold bg-light text-dark border-0 btn-quick" data-val="10000">10K</button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100 btn-sm py-2 fw-semibold bg-light text-dark border-0 btn-quick" data-val="20000">20K</button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100 btn-sm py-2 fw-semibold bg-light text-dark border-0 btn-quick" data-val="50000">50K</button></div>
                            <div class="col-3"><button type="button" class="btn btn-outline-secondary w-100 btn-sm py-2 fw-semibold bg-light text-dark border-0 btn-quick" data-val="100000">100K</button></div>
                        </div>

                        <!-- Informasi Kembalian -->
                        <div class="p-3 mb-4 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 d-flex justify-content-between align-items-center">
                            <span class="text-success fw-semibold">Kembalian</span>
                            <span class="fs-5 fw-bold text-success" id="displayKembalian">Rp 0</span>
                        </div>
                    </div>

                    <!-- Tab Debit & QRIS -->
                    <div class="tab-pane fade text-center py-4 text-muted" id="content-debit" role="tabpanel">
                        <i class="fas fa-credit-card fa-3x mb-2 text-danger"></i>
                        <p class="mb-0">Silakan gesek/tap kartu pada mesin EDC.</p>
                    </div>
                    <div class="tab-pane fade text-center py-4 text-muted" id="content-qris" role="tabpanel">
                        <div class="bg-light p-3 d-inline-block rounded border mb-2">
                            <i class="fas fa-qrcode fa-4x text-dark"></i>
                        </div>
                        <p class="mb-0 small">Scan QRIS menggunakan aplikasi e-wallet.</p>
                    </div>
                </div>

                <!-- Tombol Eksekusi -->
                <div class="d-grid">
                    <button type="button" class="btn btn-danger btn-lg py-3 fw-bold rounded-3 shadow-sm">
                        Proses Pembayaran &rarr;
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript untuk Format Rupiah & Hitung Kembalian Otomatis -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputUang = document.getElementById('inputUangDiterima');
    const displayKembalian = document.getElementById('displayKembalian');
    const totalTagihanEl = document.getElementById('displayTotalTagihan');
    
    // Ambil nilai total tagihan dari atribut data-total (misal: 7500)
    const totalTagihan = parseInt(totalTagihanEl.getAttribute('data-total')) || 0;

    // Fungsi format angka ke Rupiah (contoh: 10000 -> 10.000)
    function formatRupiah(angka) {
        let numberString = angka.replace(/[^,\d]/g, "").toString();
        let split = numberString.split(",");
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }
        return split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
    }

    // Event saat mengetik di input uang diterima
    inputUang.addEventListener('keyup', function(e) {
        // Format input jadi ada titiknya
        let nilaiRaw = this.value.replace(/\./g, '');
        this.value = formatRupiah(this.value);

        // Hitung kembalian otomatis
        let uangDiterima = parseInt(nilaiRaw) || 0;
        let kembalian = uangDiterima - totalTagihan;

        if (kembalian >= 0) {
            displayKembalian.textContent = "Rp " + formatRupiah(kembalian.toString());
            displayKembalian.classList.remove('text-danger');
            displayKembalian.classList.add('text-success');
        } else {
            let kurang = Math.abs(kembalian);
            displayKembalian.textContent = "-Rp " + formatRupiah(kurang.toString());
            displayKembalian.classList.remove('text-success');
            displayKembalian.classList.add('text-danger');
        }
    });

    // Event tombol nominal cepat (Quick Cash 10K, 20K, dst)
    const btnQuickList = document.querySelectorAll('.btn-quick');
    btnQuickList.forEach(btn => {
        btn.addEventListener('click', function() {
            let val = this.getAttribute('data-val');
            inputUang.value = formatRupiah(val);
            
            // Trigger hitung kembalian otomatis
            let uangDiterima = parseInt(val);
            let kembalian = uangDiterima - totalTagihan;
            displayKembalian.textContent = "Rp " + formatRupiah(kembalian.toString());
        });
    });
});
</script>
</body>
</html>
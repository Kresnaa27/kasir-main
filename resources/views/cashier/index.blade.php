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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-cash-register me-2 text-warning"></i> Kasir Minimarket</a>
            <div class="d-flex align-items-center text-light">
                <span class="me-3"><i class="fas fa-user-tie me-1"></i> Kasir: <strong>{{ $namaKasir }}</strong></span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</button>
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
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-barcode"></i></span>
                            <input type="text" class="form-control form-control-lg" placeholder="Scan Barcode atau Ketik Nama Produk di sini..." autofocus>
                            <button class="btn btn-primary" type="button">Cari</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-wrapper">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="table-dark sticky-top">
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
                            <div class="bg-dark text-warning p-3 rounded text-end mb-4 shadow-sm">
                                <span class="d-block text-white-50 small uppercase">Total Tagihan</span>
                                <h1 class="fw-bold mb-0">Rp 25.500</h1>
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
                            <button class="btn btn-success btn-lg py-3 fw-bold shadow-sm">
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
</body>
</html>
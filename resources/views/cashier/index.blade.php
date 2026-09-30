<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir KOPDES - POS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Memanggil CSS dan JS melalui Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- MODAL QRIS -->
    <div class="modal fade" id="qrisModal" tabindex="-1" aria-labelledby="qrisModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg text-center p-4">
                <h5 class="fw-bold text-danger mb-3">Scan QRIS Kasir</h5>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=Pembayaran_Kasir_ID_{{ auth()->user()->id }}"
                    class="img-fluid rounded shadow-sm mb-3" alt="QRIS Kasir">
                <h6 class="fw-bold mb-1">{{ auth()->user()->name }}</h6>
                <p class="text-muted small">Total Tagihan: <span id="qrisTotalTagihan" class="fw-bold text-dark">Rp 0</span></p>
                <button type="button" class="btn btn-success w-100 fw-bold" id="confirmQrisBtn">Konfirmasi Pembayaran Selesai</button>
            </div>
        </div>
    </div>

    <div class="main-wrapper">
        <!-- SIDEBAR KIRI -->
        <div class="sidebar">
            <div class="sidebar-logo">K</div>
            <div class="d-flex flex-column align-items-center w-100">
                <button class="sidebar-menu-btn active" title="Produk & Kasir">
                    <i class="fas fa-th-large fa-lg"></i>
                </button>
                <button class="sidebar-menu-btn" title="Riwayat Penjualan Hari Ini" data-bs-toggle="modal" data-bs-target="#historyModal">
                    <i class="fas fa-receipt fa-lg"></i>
                </button>
                <button class="sidebar-menu-btn" title="Manajemen Stok" data-bs-toggle="modal" data-bs-target="#stockModal">
                    <i class="fas fa-boxes fa-lg"></i>
                    <span class="badge-warning-dot" id="sidebarWarningDot"></span>
                </button>
                <button class="sidebar-menu-btn mt-3" title="Kasir Shift" data-bs-toggle="modal" data-bs-target="#shiftModal">
                    <i class="fas fa-user-shield fa-lg"></i>
                </button>
            </div>

            <div class="mt-auto">
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-menu-btn text-danger" title="Logout">
                        <i class="fas fa-sign-out-alt fa-lg"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- KONTEN UTAMA -->
        <div class="content-area">
            <!-- Navbar Header -->
            <nav class="navbar navbar-expand-lg navbar-custom px-4 py-2 mb-4 shadow-sm">
                <div class="container-fluid">
                    <span class="navbar-brand fw-bold text-danger d-flex align-items-center fs-6">
                        <i class="fas fa-cash-register me-2"></i> Kasir KOPDES - Point of Sale
                    </span>
                    <div class="d-flex align-items-center">
                        
                        <!-- JAM & TANGGAL REALTIME -->
                        <div class="bg-white px-3 py-1 rounded-pill border me-3 d-flex align-items-center shadow-sm">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <span class="small fw-bold text-dark" id="realtimeClock">Memuat waktu...</span>
                        </div>

                        <div class="bg-light px-3 py-1 rounded-pill border me-3 d-flex align-items-center">
                            <i class="fas fa-user-circle text-danger me-2"></i>
                            <span class="small fw-bold text-secondary">Kasir: <span class="text-dark" id="currentCashierName">{{ auth()->user()->name }}</span></span>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="row g-4">
                <!-- KOLOM KIRI: Katalog Produk & Kategori -->
                <div class="col-lg-8">
                    <!-- Search Bar -->
                    <div class="input-group mb-4 shadow-sm rounded-pill overflow-hidden bg-white border">
                        <span class="input-group-text bg-white border-0 ps-4 text-danger"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchProduct" class="form-control border-0 shadow-none py-3" placeholder="Cari nama produk di sini...">
                        <button class="btn btn-danger px-4 fw-semibold" type="button">Cari</button>
                    </div>

                    <!-- Kategori Utama -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="card category-card active p-3 shadow-sm filter-btn" data-category="semua">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-th-large fa-2x me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Semua</h6>
                                            <small class="opacity-75" style="font-size: 0.75rem;">{{ count($products) }} Produk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Makanan -->
                        <div class="col-md-3 col-6">
                            <div class="card category-card p-3 shadow-sm dropdown-toggle-custom" data-target="dropdown-makanan" style="cursor: pointer;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-utensils fa-2x text-danger me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Makanan</h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">Sub-kategori <i class="fas fa-chevron-down ms-1" style="font-size: 0.65rem;"></i></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-sm border-0 position-absolute mt-1 p-2 bg-white rounded-3 sub-dropdown-menu" id="dropdown-makanan" style="display: none; z-index: 99; width: 220px;">
                                <a href="#sec-makanan-ringan" class="dropdown-item py-1 px-2 rounded small fw-semibold text-dark text-decoration-none d-block scroll-link" data-target-section="sec-makanan-ringan">↳ Makanan Ringan & Snack</a>
                                <a href="#sec-makanan-instan" class="dropdown-item py-1 px-2 rounded small fw-semibold text-dark text-decoration-none d-block scroll-link mt-1" data-target-section="sec-makanan-instan">↳ Makanan Instan</a>
                            </div>
                        </div>

                        <!-- Dropdown Minuman -->
                        <div class="col-md-3 col-6">
                            <div class="card category-card p-3 shadow-sm dropdown-toggle-custom" data-target="dropdown-minuman" style="cursor: pointer;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-glass-whiskey fa-2x text-danger me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Minuman</h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">Sub-kategori <i class="fas fa-chevron-down ms-1" style="font-size: 0.65rem;"></i></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-sm border-0 position-absolute mt-1 p-2 bg-white rounded-3 sub-dropdown-menu" id="dropdown-minuman" style="display: none; z-index: 99; width: 220px;">
                                <a href="#sec-minuman-dingin" class="dropdown-item py-1 px-2 rounded small fw-semibold text-dark text-decoration-none d-block scroll-link" data-target-section="sec-minuman-dingin">↳ Minuman Dingin & Botol</a>
                            </div>
                        </div>

                        <!-- Dropdown Kebutuhan -->
                        <div class="col-md-3 col-6">
                            <div class="card category-card p-3 shadow-sm dropdown-toggle-custom" data-target="dropdown-harian" style="cursor: pointer;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-shopping-basket fa-2x text-danger me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Kebutuhan</h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">Sub-kategori <i class="fas fa-chevron-down ms-1" style="font-size: 0.65rem;"></i></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-sm border-0 position-absolute mt-1 p-2 bg-white rounded-3 sub-dropdown-menu" id="dropdown-harian" style="display: none; z-index: 99; width: 220px;">
                                <a href="#sec-kebersihan" class="dropdown-item py-1 px-2 rounded small fw-semibold text-dark text-decoration-none d-block scroll-link" data-target-section="sec-kebersihan">↳ Kebersihan & Rumah Tangga</a>
                            </div>
                        </div>
                    </div>

                    <!-- Katalog Produk Tersusun Berkelompok -->
                    <div id="structuredCatalogContainer"></div>
                </div>

                <!-- KOLOM KANAN: Invoice Pembayaran -->
                <div class="col-lg-4">
                    <div class="cart-section p-4 sticky-top" style="top: 20px;">
                        <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-shopping-cart text-danger me-2"></i> Invoice Pembayaran</h5>
                        <hr>

                        <div class="table-responsive mb-3" style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-sm align-middle">
                                <thead class="text-muted small">
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody" class="small">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Keranjang masih kosong</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-dark text-white p-3 rounded-3 mb-3 text-center">
                            <small class="text-uppercase text-light opacity-75" style="font-size: 0.7rem; letter-spacing: 1px;">Total Tagihan</small>
                            <h3 class="fw-bold text-warning mb-0" id="grandTotal">Rp 0</h3>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Metode Pembayaran</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <button type="button" class="btn w-100 py-2 payment-method-btn active" data-method="cash">
                                        <i class="fas fa-money-bill-wave d-block mb-1"></i> Cash
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn w-100 py-2 payment-method-btn" data-method="debit">
                                        <i class="fas fa-credit-card d-block mb-1"></i> Debit
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn w-100 py-2 payment-method-btn" data-method="qris">
                                        <i class="fas fa-qrcode d-block mb-1"></i> QRIS
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Input Cash / Tunai -->
                        <div class="mb-3" id="cashPaymentSection">
                            <label class="form-label text-secondary small fw-semibold">Jumlah Uang Tunai</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold text-secondary">Rp</span>
                                <input type="text" id="cashInput" class="form-control fw-bold text-end" placeholder="0" inputmode="numeric">
                            </div>
                        </div>

                        <div class="mb-3 bg-light p-2 rounded-3 border d-flex flex-column" id="changeSection">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-secondary fw-semibold">Uang Kembalian:</span>
                            </div>
                            <div class="text-end" id="changeOutputContainer">
                                <span class="fw-bold text-success" id="changeOutput" style="font-size: 1.1rem;">Rp 0</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-danger py-2 fw-bold shadow-sm" id="processBtn">
                                <i class="fas fa-check-circle me-1"></i> Proses Transaksi & Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL RIWAYAT -->
    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-receipt me-2"></i> Riwayat Penjualan Hari Ini</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle small">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Total Tagihan</th>
                                    <th>Pembayaran</th>
                                    <th>Kembalian</th>
                                    <th>Detail Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($todayTransactions) && count($todayTransactions) > 0)
                                    @foreach($todayTransactions as $tx)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $tx->created_at->format('H:i') }} WITA</td>
                                            <td class="fw-bold text-success text-end">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($tx->pay_amount, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($tx->change_amount, 0, ',', '.') }}</td>
                                            <td>
                                                <ul class="mb-0 ps-3">
                                                    @foreach($tx->details as $detail)
                                                        <li>{{ $detail->product->name ?? 'Produk' }} x {{ $detail->quantity }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi untuk hari ini.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL RESTOCK -->
    <div class="modal fade" id="stockModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-boxes me-2"></i> Manajemen & Restock Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pilih Produk</label>
                        <select class="form-select form-select-sm" id="restockProductSelect"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tambah Jumlah Stok (Qty)</label>
                        <input type="number" id="restockQtyInput" class="form-control form-control-sm" value="10" min="1">
                    </div>
                    <button type="button" class="btn btn-danger w-100 fw-bold btn-sm py-2" id="saveRestockBtn">
                        <i class="fas fa-plus-circle me-1"></i> Simpan & Restock
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Variabel Global untuk JS -->
    <script>
        window.productsData = @json($products ?? []).map(p => ({
            id: p.id,
            name: p.name,
            category: p.category ? p.category.name.toLowerCase() : 'lainnya',
            price: p.price,
            stock: p.stock,
            img: p.image ? `/storage/${p.image}` : 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300'
        }));
        window.transactionUrl = "{{ route('cashier.transaction') }}";
        window.restockUrl = "{{ route('cashier.restock') }}";
        window.authUserName = "{{ auth()->user()->name ?? 'Kasir' }}";
    </script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
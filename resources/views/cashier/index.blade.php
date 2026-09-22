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
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        
        .main-wrapper { display: flex; width: 100%; min-height: 100vh; }
        .sidebar {
            width: 80px;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            z-index: 1000;
        }
        .sidebar-logo {
            width: 45px;
            height: 45px;
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        }
        .sidebar-menu-btn {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: none;
            background: transparent;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            transition: all 0.2s;
            position: relative;
            cursor: pointer;
        }
        .sidebar-menu-btn:hover, .sidebar-menu-btn.active {
            background-color: #dc3545;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
        }
        .badge-warning-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 10px;
            height: 10px;
            background-color: #ffc107;
            border-radius: 50%;
            border: 2px solid #fff;
            display: none;
        }

        .content-area {
            margin-left: 80px;
            width: calc(100% - 80px);
            padding: 20px 30px;
        }

        .navbar-custom { background-color: #ffffff; border-bottom: 1px solid #dee2e6; border-radius: 12px; }
        .category-card {
            border: none;
            border-radius: 12px;
            transition: all 0.2s;
            cursor: pointer;
            background: #ffffff;
            color: #212529;
        }
        .category-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .category-card.active {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }
        .category-card.active .text-muted { color: #f8f9fa !important; }
        .category-card.active i { color: #ffffff !important; }
        
        .product-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.2s;
            background: #fff;
        }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.08); }
        .product-img-wrapper {
            height: 130px;
            background-color: #f1f3f5;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .product-img { width: 100%; height: 100%; object-fit: cover; }
        .cart-section { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

        .payment-method-btn {
            border: 1px solid #ced4da;
            background: #f8f9fa;
            color: #495057;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .payment-method-btn.active {
            background-color: #dc3545 !important;
            color: #ffffff !important;
            border-color: #dc3545 !important;
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
        }

        #changeOutputContainer {
            word-break: break-word;
            overflow: hidden;
        }
        #changeOutput {
            font-size: 1.1rem !important;
            text-align: right;
            display: inline-block;
            max-width: 100%;
        }

        .badge-shift-active-pagi { background-color: #0dcaf0 !important; color: #000 !important; box-shadow: 0 0 10px rgba(13, 202, 240, 0.6); font-weight: bold; }
        .badge-shift-active-siang { background-color: #ffc107 !important; color: #000 !important; box-shadow: 0 0 10px rgba(255, 193, 7, 0.6); font-weight: bold; }
        .badge-shift-active-sore { background-color: #fd7e14 !important; color: #fff !important; box-shadow: 0 0 10px rgba(253, 126, 20, 0.6); font-weight: bold; }
        .badge-shift-inactive { background-color: #6c757d !important; color: #fff !important; opacity: 0.6; }
    </style>
</head>
<body>

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
                <button class="sidebar-menu-btn mt-3" title="Kasir Shift: Dewa" data-bs-toggle="modal" data-bs-target="#shiftModal">
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
                        <div class="bg-light px-3 py-1 rounded-pill border me-3 d-flex align-items-center">
                            <i class="fas fa-user-circle text-danger me-2"></i>
                            <span class="small fw-bold text-secondary">Kasir Shift: <span class="text-dark" id="currentCashierName">Dewa</span></span>
                        </div>
                        <span class="badge bg-warning text-dark px-2 py-1 small fw-semibold" id="globalStockAlert" style="font-size: 0.75rem; display: none;">
                            <i class="fas fa-exclamation-triangle me-1"></i> Stok Menipis!
                        </span>
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

                    <!-- Kategori Menu Dinamis -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="card category-card active p-3 shadow-sm filter-btn" data-category="semua">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-th-large fa-2x me-3"></i>
                                    <div>
                                        <h6 class="fw-bold mb-0">Semua</h6>
                                        <small class="opacity-75" style="font-size: 0.75rem;">{{ count($products) }} Produk</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($categories as $category)
                        <div class="col-md-3 col-6">
                            <div class="card category-card p-3 shadow-sm filter-btn" data-category="{{ Str::slug($category->name) }}">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $category->icon ?? 'fas fa-utensils' }} fa-2x text-danger me-3"></i>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $category->name }}</h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $category->products_count ?? $category->products->count() }} Produk</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Daftar Produk -->
                    <h5 class="fw-bold mb-3 text-dark">Katalog Produk</h5>
                    <div class="row g-3" id="productListContainer">
                        <!-- Render via JS -->
                    </div>
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

                        <!-- Pilihan Metode Pembayaran -->
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
                            <label class="form-label text-secondary small fw-semibold">Jumlah Uang Tunai (Rp)</label>
                            <input type="text" id="cashInput" class="form-control fw-bold text-end" value="" placeholder="0" inputmode="numeric">
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
                            <button class="btn btn-outline-secondary btn-sm fw-semibold" id="clearCartBtn">
                                <i class="fas fa-trash-alt me-1"></i> Kosongkan Keranjang
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL RIWAYAT PENJUALAN HARI INI -->
    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-receipt me-2"></i> Catatan & Riwayat Penjualan Pegawai Hari Ini</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3 py-2">
                        <div>
                            <i class="fas fa-calendar-alt text-danger me-1"></i> <span id="currentDateText" class="fw-bold text-dark">--</span>
                        </div>
                        <div>
                            <i class="fas fa-clock text-danger me-1"></i> <span id="currentTimeText" class="fw-bold text-dark">--</span>
                        </div>
                    </div>

                    <!-- Tabel Transaksi Hari Ini Dinamis dari Database -->
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
                            <tbody id="todayHistoryTableBody">
                                @forelse($todayTransactions as $tx)
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
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi untuk hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-4 fw-semibold" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL RESTOCK STOK -->
    <div class="modal fade" id="stockModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-boxes me-2"></i> Manajemen & Restock Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                        <div><strong>Perhatian!</strong> Produk dengan stok menipis (&le; 5) perlu segera di-restock.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pilih Produk untuk Restock</label>
                        <select class="form-select form-select-sm" id="restockProductSelect"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tambah Jumlah Stok (Qty)</label>
                        <input type="number" id="restockQtyInput" class="form-control form-control-sm" value="10" min="1">
                    </div>
                    <button type="button" class="btn btn-danger w-100 fw-bold btn-sm py-2" id="saveRestockBtn">
                        <i class="fas fa-plus-circle me-1"></i> Simpan & Restock Produk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SHIFT KASIR -->
    <div class="modal fade" id="shiftModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-user-shield me-2"></i> Pengaturan Shift & Kasir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <span class="text-muted small d-block">Kasir Aktif Saat Ini:</span>
                        <h5 class="fw-bold text-danger mb-0"><span id="activeShiftName">Dewa</span> <span class="badge bg-success fs-6 ms-2" id="activeShiftTime">Shift Pagi</span></h5>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Ganti Kasir / Shift Berikutnya</label>
                        <select class="form-select form-select-sm" id="switchCashierSelect">
                            <option value="Dewa">Dewa (Shift Pagi)</option>
                            <option value="Kresnaa">Kresnaa (Shift Siang)</option>
                            <option value="Surya">Surya (Shift Sore)</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-dark w-100 fw-bold btn-sm py-2" id="applyShiftBtn">
                        <i class="fas fa-sync-alt me-1"></i> Terapkan & Ganti Kasir Aktif
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript Dinamis -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil Data Produk dari Database via Laravel Blade
        let products = @json($products).map(p => ({
            id: p.id,
            name: p.name,
            category: p.category ? p.category.name.toLowerCase().replace(/\s+/g, '-') : 'lainnya',
            price: p.price,
            stock: p.stock,
            img: p.image ? `/storage/${p.image}` : 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300'
        }));

        let activeCashier = "Dewa";
        let activePaymentMethod = "cash";
        let cart = [];
        let currentFilter = 'semua';
        let searchKeyword = '';

        const productListContainer = document.getElementById('productListContainer');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('searchProduct');
        const cartTableBody = document.getElementById('cartTableBody');
        const grandTotalEl = document.getElementById('grandTotal');
        const cashInput = document.getElementById('cashInput');
        const changeOutput = document.getElementById('changeOutput');
        const clearCartBtn = document.getElementById('clearCartBtn');
        const processBtn = document.getElementById('processBtn');
        const globalStockAlert = document.getElementById('globalStockAlert');
        const sidebarWarningDot = document.getElementById('sidebarWarningDot');
        const restockProductSelect = document.getElementById('restockProductSelect');
        const restockQtyInput = document.getElementById('restockQtyInput');
        const saveRestockBtn = document.getElementById('saveRestockBtn');
        const currentCashierName = document.getElementById('currentCashierName');
        const activeShiftName = document.getElementById('activeShiftName');
        const switchCashierSelect = document.getElementById('switchCashierSelect');
        const applyShiftBtn = document.getElementById('applyShiftBtn');
        const currentDateText = document.getElementById('currentDateText');
        const currentTimeText = document.getElementById('currentTimeText');
        const paymentMethodBtns = document.querySelectorAll('.payment-method-btn');
        const cashPaymentSection = document.getElementById('cashPaymentSection');
        const changeSection = document.getElementById('changeSection');

        // Real-time Date Time
        function updateDateTime() {
            let now = new Date();
            let optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            currentDateText.innerText = now.toLocaleDateString('id-ID', optionsDate);
            currentTimeText.innerText = now.toLocaleTimeString('id-ID') + " WITA";
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Pilih Metode Pembayaran
        paymentMethodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                paymentMethodBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activePaymentMethod = this.getAttribute('data-method');

                if (activePaymentMethod === 'cash') {
                    cashPaymentSection.style.display = 'block';
                    changeSection.style.display = 'flex';
                } else {
                    cashPaymentSection.style.display = 'none';
                    changeSection.style.display = 'none';
                }
            });
        });

        // Render Katalog Produk
        function renderProducts() {
            productListContainer.innerHTML = '';
            let hasLowStock = false;
            restockProductSelect.innerHTML = '';

            products.forEach(prod => {
                if (currentFilter !== 'semua' && prod.category !== currentFilter) return;
                if (searchKeyword && !prod.name.toLowerCase().includes(searchKeyword)) return;

                let isLowStock = prod.stock <= 5;
                if (isLowStock) hasLowStock = true;

                let opt = document.createElement('option');
                opt.value = prod.id;
                opt.textContent = `${prod.name} (Stok: ${prod.stock}${isLowStock ? ' - Menipis!' : ''})`;
                restockProductSelect.appendChild(opt);

                let cardHtml = `
                    <div class="col-md-4 col-sm-6 product-item">
                        <div class="card product-card h-100 ${isLowStock ? 'border-warning border-2' : ''}">
                            <div class="product-img-wrapper">
                                ${isLowStock ? `
                                    <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2 shadow-sm" style="font-size: 0.65rem; z-index: 2;">
                                        <i class="fas fa-exclamation-triangle"></i> Stok Menipis (${prod.stock})
                                    </span>
                                ` : `
                                    <span class="position-absolute top-0 end-0 badge bg-dark text-white m-2 opacity-75 shadow-sm" style="font-size: 0.7rem; z-index: 2;">
                                        Stok: ${prod.stock}
                                    </span>
                                `}
                                <img src="${prod.img}" class="product-img" alt="${prod.name}" onerror="this.src='https://images.unsplash.com/photo-1542838132-92c53300491e?w=300'">
                            </div>
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge bg-danger bg-opacity-10 text-danger mb-1" style="font-size: 0.65rem; text-transform: capitalize;">${prod.category.replace('-', ' ')}</span>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">${prod.name}</h6>
                                    <p class="text-danger fw-bold mb-3">Rp ${prod.price.toLocaleString('id-ID')}</p>
                                </div>
                                <button class="btn btn-outline-danger btn-sm w-100 fw-semibold rounded-pill add-to-cart-btn" data-id="${prod.id}" ${prod.stock <= 0 ? 'disabled' : ''}>
                                    <i class="fas fa-plus me-1"></i> ${prod.stock <= 0 ? 'Stok Habis' : 'Tambah'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                productListContainer.insertAdjacentHTML('beforeend', cardHtml);
            });

            if (hasLowStock) {
                globalStockAlert.style.display = 'inline-block';
                sidebarWarningDot.style.display = 'block';
            } else {
                globalStockAlert.style.display = 'none';
                sidebarWarningDot.style.display = 'none';
            }

            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    addToCart(parseInt(this.getAttribute('data-id')));
                });
            });
        }

        function addToCart(prodId) {
            let prod = products.find(p => p.id === prodId);
            if (!prod || prod.stock <= 0) return;

            prod.stock -= 1;
            let cartItem = cart.find(item => item.id === prodId);
            if (cartItem) {
                cartItem.qty += 1;
            } else {
                cart.push({ id: prod.id, name: prod.name, price: prod.price, qty: 1 });
            }
            renderProducts();
            renderCart();
        }

        function renderCart() {
            cartTableBody.innerHTML = '';
            if (cart.length === 0) {
                cartTableBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Keranjang masih kosong</td></tr>';
                grandTotalEl.innerText = 'Rp 0';
                changeOutput.innerText = 'Rp 0';
                return;
            }

            let total = 0;
            cart.forEach((item, index) => {
                let subtotal = item.price * item.qty;
                total += subtotal;

                let row = `
                    <tr>
                        <td>${item.name}</td>
                        <td>
                            <div class="input-group input-group-sm" style="width: 80px;">
                                <button class="btn btn-outline-secondary px-1 py-0 decrease-qty" data-index="${index}">-</button>
                                <input type="text" value="${item.qty}" class="form-control text-center p-0" readonly style="background: #fff;">
                                <button class="btn btn-outline-secondary px-1 py-0 increase-qty" data-index="${index}">+</button>
                            </div>
                        </td>
                        <td class="text-end">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>
                `;
                cartTableBody.insertAdjacentHTML('beforeend', row);
            });

            grandTotalEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
            calculateChange();

            document.querySelectorAll('.increase-qty').forEach(btn => {
                btn.addEventListener('click', function() {
                    let idx = this.getAttribute('data-index');
                    let cartItem = cart[idx];
                    let prod = products.find(p => p.id === cartItem.id);
                    if (prod.stock > 0) {
                        prod.stock -= 1;
                        cartItem.qty += 1;
                        renderProducts();
                        renderCart();
                    }
                });
            });

            document.querySelectorAll('.decrease-qty').forEach(btn => {
                btn.addEventListener('click', function() {
                    let idx = this.getAttribute('data-index');
                    let cartItem = cart[idx];
                    let prod = products.find(p => p.id === cartItem.id);
                    prod.stock += 1;
                    cartItem.qty -= 1;
                    if (cartItem.qty <= 0) cart.splice(idx, 1);
                    renderProducts();
                    renderCart();
                });
            });
        }

        function calculateChange() {
            if (activePaymentMethod !== 'cash') return;
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let cashRaw = cashInput.value.replace(/[^0-9]/g, '');
            let cashAmount = parseInt(cashRaw, 10) || 0;
            let change = cashAmount - total;
            
            if (change >= 0) {
                changeOutput.innerText = 'Rp ' + change.toLocaleString('id-ID');
                changeOutput.className = 'fw-bold text-success';
            } else {
                changeOutput.innerText = 'Kurang Rp ' + Math.abs(change).toLocaleString('id-ID');
                changeOutput.className = 'fw-bold text-danger';
            }
        }

        cashInput.addEventListener('input', function() {
            let rawValue = this.value.replace(/[^0-9]/g, '');
            if (rawValue === '') {
                this.value = '';
                calculateChange();
                return;
            }
            let numericValue = parseInt(rawValue, 10);
            this.value = 'Rp ' + numericValue.toLocaleString('id-ID');
            calculateChange();
        });

        clearCartBtn.addEventListener('click', function() {
            cart.forEach(cartItem => {
                let prod = products.find(p => p.id === cartItem.id);
                if (prod) prod.stock += cartItem.qty;
            });
            cart = [];
            cashInput.value = '';
            changeOutput.innerText = 'Rp 0';
            renderProducts();
            renderCart();
        });

        // Simpan Transaksi ke Database via Endpoint Backend
        processBtn.addEventListener('click', function() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }
            
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let cashRaw = cashInput.value.replace(/[^0-9]/g, '');
            let cash = parseInt(cashRaw, 10) || total;

            if (activePaymentMethod === 'cash' && cash < total) {
                alert('Jumlah uang tunai kurang dari total tagihan!');
                return;
            }

            let payload = {
                pay_amount: cash,
                notes: `Metode: ${activePaymentMethod.toUpperCase()} | Kasir: ${activeCashier}`,
                cart: cart.map(item => ({
                    id: item.id,
                    price: item.price,
                    quantity: item.qty
                }))
            };

            fetch('{{ route("cashier.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Transaksi berhasil diproses & disimpan ke database!');
                    window.location.reload(); // Muat ulang agar riwayat & stok terupdate dari DB
                } else {
                    alert('Gagal memproses transaksi: ' + (data.message || 'Error'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            });
        });

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-category');
                renderProducts();
            });
        });

        searchInput.addEventListener('keyup', function() {
            searchKeyword = this.value.toLowerCase().trim();
            renderProducts();
        });

        saveRestockBtn.addEventListener('click', function() {
            let selectedId = parseInt(restockProductSelect.value);
            let addQty = parseInt(restockQtyInput.value) || 0;
            if (addQty <= 0) return;

            let prod = products.find(p => p.id === selectedId);
            if (prod) {
                prod.stock += addQty;
                alert(`Berhasil restock ${prod.name} sebanyak ${addQty}.`);
                renderProducts();
                renderCart();
            }
        });

        applyShiftBtn.addEventListener('click', function() {
            activeCashier = switchCashierSelect.value;
            currentCashierName.innerText = activeCashier;
            activeShiftName.innerText = activeCashier;
            alert(`Shift berhasil dialihkan ke ${activeCashier}.`);
            let modalEl = document.getElementById('shiftModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        });

        renderProducts();
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
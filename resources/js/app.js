import './bootstrap';

// ==========================================
// 1. FUNGSI JAM & TANGGAL REAL-TIME
// ==========================================
function updateRealtimeClock() {
    const clockElement = document.getElementById('realtimeClock');
    if (!clockElement) return;

    const now = new Date();
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const dayName = days[now.getDay()];
    const date = String(now.getDate()).padStart(2, '0');
    const month = months[now.getMonth()];
    const year = now.getFullYear();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    clockElement.innerText = `${dayName}, ${date} ${month} ${year} | ${hours}:${minutes}:${seconds}`;
}
// Jalankan jam setiap 1 detik
setInterval(updateRealtimeClock, 1000);
updateRealtimeClock(); // Eksekusi langsung saat halaman dimuat

// ==========================================
// 2. LOGIKA UTAMA KASIR POS
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    // Logika Toggle Sub-Dropdown Kategori
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle-custom');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            let targetId = this.getAttribute('data-target');
            let targetMenu = document.getElementById(targetId);

            document.querySelectorAll('.sub-dropdown-menu').forEach(menu => {
                if (menu.id !== targetId) menu.style.display = 'none';
            });

            targetMenu.style.display = (targetMenu.style.display === 'block') ? 'none' : 'block';
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.sub-dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });

    // Render Katalog Produk
    const structuredCatalogContainer = document.getElementById('structuredCatalogContainer');
    const searchInput = document.getElementById('searchProduct');
    let searchKeyword = '';

    function renderStructuredCatalog() {
        if (!structuredCatalogContainer) return;
        structuredCatalogContainer.innerHTML = '';

        let groups = [
            { id: 'sec-makanan-ringan', title: '1. Makanan & Snack', subtitle: 'Makanan Ringan & Snack', filterFn: p => p.category.includes('makanan') || p.category.includes('snack') },
            { id: 'sec-makanan-instan', title: '', subtitle: 'Makanan Instan', filterFn: p => p.category.includes('instan') || p.name.toLowerCase().includes('mie') },
            { id: 'sec-minuman-dingin', title: '2. Minuman', subtitle: 'Minuman Dingin & Botol', filterFn: p => p.category.includes('minuman') || p.category.includes('drink') },
            { id: 'sec-kebersihan', title: '3. Kebutuhan Harian & Rumah Tangga', subtitle: 'Kebersihan & Perawatan', filterFn: p => p.category.includes('kebersihan') || p.category.includes('sabun') || p.category.includes('rumah') }
        ];

        groups.forEach(group => {
            let filteredProducts = window.productsData.filter(group.filterFn);
            if (searchKeyword) {
                filteredProducts = filteredProducts.filter(p => p.name.toLowerCase().includes(searchKeyword));
            }
            if (filteredProducts.length === 0 && searchKeyword) return;

            let sectionHtml = `
            <div class="mb-4 catalog-section-group" id="${group.id}">
                ${group.title ? `<h5 class="fw-bold text-dark mb-1 border-bottom pb-2"><i class="fas fa-layer-group text-danger me-2"></i>${group.title}</h5>` : ''}
                <h6 class="text-secondary fw-semibold mt-2 mb-3 ms-1" style="font-size: 0.9rem;">↳ ${group.subtitle}</h6>
                <div class="row g-3">
            `;

            filteredProducts.forEach(prod => {
                let isLowStock = prod.stock <= 5;
                sectionHtml += `
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
                                <span class="badge bg-danger bg-opacity-10 text-danger mb-1" style="font-size: 0.65rem; text-transform: capitalize;">${prod.category}</span>
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
            });

            sectionHtml += `</div></div>`;
            structuredCatalogContainer.insertAdjacentHTML('beforeend', sectionHtml);
        });

        // Event listener ke tombol tambah produk
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                addToCart(parseInt(this.getAttribute('data-id')));
            });
        });
    }

    document.querySelectorAll('.scroll-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            let targetSecId = this.getAttribute('data-target-section');
            let targetElement = document.getElementById(targetSecId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            searchKeyword = this.value.toLowerCase().trim();
            renderStructuredCatalog();
        });
    }

    renderStructuredCatalog();
});

let cart = [];
let paymentMethod = 'cash';

// FUNGSI 1: TAMBAH KE KERANJANG
window.addToCart = function(id) {
    let product = window.productsData.find(p => p.id === id);
    if (!product || product.stock <= 0) {
        alert('Maaf, stok barang ini sudah habis!');
        return;
    }

    let cartItem = cart.find(c => c.id === id);
    if (cartItem) {
        if (cartItem.qty >= product.stock) {
            alert('Gagal: Kuantitas melebihi stok yang tersedia di gudang!');
            return;
        }
        cartItem.qty++;
    } else {
        cart.push({ id: product.id, name: product.name, price: product.price, qty: 1, stock: product.stock });
    }
    updateCartUI();
}

// FUNGSI 2: PERBARUI TAMPILAN KERANJANG
function updateCartUI() {
    let tbody = document.getElementById('cartTableBody');
    let grandTotal = 0;
    if (!tbody) return;
    tbody.innerHTML = '';

    if (cart.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Keranjang masih kosong</td></tr>';
    } else {
        cart.forEach((item, index) => {
            let subtotal = item.price * item.qty;
            grandTotal += subtotal;
            tbody.innerHTML += `
            <tr>
                <td>
                    <div class="fw-bold text-dark">${item.name}</div>
                    <small class="text-danger">Rp ${item.price.toLocaleString('id-ID')}</small>
                </td>
                <td>
                    <div class="input-group input-group-sm" style="width: 80px;">
                        <button class="btn btn-outline-secondary" onclick="changeQty(${index}, -1)">-</button>
                        <input type="text" class="form-control text-center px-0" value="${item.qty}" readonly>
                        <button class="btn btn-outline-secondary" onclick="changeQty(${index}, 1)">+</button>
                    </div>
                </td>
                <td class="text-end fw-bold">Rp ${subtotal.toLocaleString('id-ID')}</td>
            </tr>
            `;
        });
    }

    document.getElementById('grandTotal').innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    calculateChange(grandTotal);
}

// FUNGSI 3: UBAH KUANTITAS BARANG
window.changeQty = function(index, amount) {
    let item = cart[index];
    item.qty += amount;

    if (item.qty > item.stock) item.qty = item.stock;
    if (item.qty <= 0) cart.splice(index, 1);
    updateCartUI();
}

// FUNGSI 4: FORMAT INPUT TUNAI
const cashInput = document.getElementById('cashInput');
if (cashInput) {
    cashInput.addEventListener('input', function (e) {
        let rawValue = this.value.replace(/[^0-9]/g, '');
        if (rawValue) {
            this.value = parseInt(rawValue, 10).toLocaleString('id-ID');
        } else {
            this.value = '';
        }
        let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        calculateChange(total);
    });
}

function calculateChange(total) {
    let inputEl = document.getElementById('cashInput');
    if (!inputEl) return;

    let paidAmount = parseInt(inputEl.value.replace(/\./g, '')) || 0;
    let change = paidAmount - total;
    let changeOutput = document.getElementById('changeOutput');

    if (change < 0) {
        changeOutput.innerText = "Uang Kurang!";
        changeOutput.classList.replace('text-success', 'text-danger');
    } else {
        changeOutput.innerText = `Rp ${change.toLocaleString('id-ID')}`;
        changeOutput.classList.replace('text-danger', 'text-success');
    }
}

// FUNGSI 5: PEMILIHAN METODE PEMBAYARAN
document.querySelectorAll('.payment-method-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.payment-method-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        paymentMethod = this.getAttribute('data-method');

        if (paymentMethod === 'qris' || paymentMethod === 'debit') {
            document.getElementById('cashPaymentSection').style.display = 'none';
            document.getElementById('changeSection').style.display = 'none';
        } else {
            document.getElementById('cashPaymentSection').style.display = 'block';
            document.getElementById('changeSection').style.display = 'flex';
        }
    });
});

// FUNGSI 6: PROSES TRANSAKSI
document.getElementById('processBtn')?.addEventListener('click', function () {
    if (cart.length === 0) return alert('Keranjang belanja masih kosong!');

    let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

    if (paymentMethod === 'qris') {
        document.getElementById('qrisTotalTagihan').innerText = `Rp ${total.toLocaleString('id-ID')}`;
        var qrisModal = new bootstrap.Modal(document.getElementById('qrisModal'));
        qrisModal.show();

        document.getElementById('confirmQrisBtn').onclick = function () {
            qrisModal.hide();
            sendTransactionData(total);
        };
    } else {
        let paidAmount = parseInt(document.getElementById('cashInput').value.replace(/\./g, '')) || 0;
        if (paidAmount < total) return alert('Uang tunai pelanggan tidak mencukupi!');
        sendTransactionData(paidAmount);
    }
});

// FUNGSI 7: KIRIM DATA KE LARAVEL AJAX
function sendTransactionData(paidAmount) {
    fetch(window.transactionUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cart: cart,
            payment_method: paymentMethod,
            paid_amount: paidAmount
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            printReceipt(data.invoice, cart, paidAmount);
            alert('Transaksi Sukses Disimpan!');
            location.reload(); // Refresh halaman setelah sukses
        } else {
            alert('Gagal Memproses Transaksi: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// FUNGSI 8: CETAK STRUK
function printReceipt(invoice, cartData, paidAmount) {
    let total = cartData.reduce((sum, item) => sum + (item.price * item.qty), 0);
    let change = paymentMethod === 'qris' ? 0 : paidAmount - total;
    let kasirName = window.authUserName;

    let receiptWindow = window.open('', '_blank', 'width=400,height=600');
    let date = new Date().toLocaleString('id-ID');

    let html = `
    <html><head><title>Struk Pembayaran - ${invoice}</title>
    <style>body { font-family: monospace; padding: 20px; } .text-center { text-align: center; } .fw-bold { font-weight: bold; } table { width: 100%; border-collapse: collapse; margin-bottom: 10px; } th, td { padding: 5px 0; } .border-bottom { border-bottom: 1px dashed #000; margin-bottom:10px; padding-bottom:10px; } </style>
    </head><body>
        <div class="text-center border-bottom">
            <h2>KASIR TOKO KITA</h2>
            <p>Invoice: ${invoice}<br>Kasir: ${kasirName}<br>Waktu: ${date}</p>
        </div>
        <table>
    `;

    cartData.forEach(item => {
        html += `<tr><td>${item.name} x${item.qty}</td><td style="text-align: right;">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</td></tr>`;
    });

    html += `
        </table>
        <div class="border-bottom"></div>
        <table>
            <tr><td class="fw-bold">Total Tagihan</td><td style="text-align: right;" class="fw-bold">Rp ${total.toLocaleString('id-ID')}</td></tr>
            <tr><td>Metode Pembayaran</td><td style="text-align: right; text-transform: uppercase;">${paymentMethod}</td></tr>
            <tr><td>Dibayar</td><td style="text-align: right;">Rp ${paidAmount.toLocaleString('id-ID')}</td></tr>
            <tr><td>Kembalian</td><td style="text-align: right;">Rp ${change.toLocaleString('id-ID')}</td></tr>
        </table>
        <div class="text-center" style="margin-top: 20px;">
            <p>Terima Kasih Atas Kunjungan Anda!</p>
        </div>
        <script>window.print(); window.close();<\/script>
    </body></html>
    `;
    receiptWindow.document.write(html);
    receiptWindow.document.close();
}
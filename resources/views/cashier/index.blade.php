<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Coffee Shop</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coffee: {
                            50: '#FDFBF7',
                            100: '#F4EAE1',
                            200: '#E6D2C1',
                            600: '#8C5A3C',
                            800: '#4A2E1B',
                            900: '#2C1A11',
                        },
                        sage: {
                            100: '#E8F0EC',
                            500: '#84A98C',
                            600: '#6B8E78',
                            700: '#52715E',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-coffee-50 font-sans antialiased text-coffee-900 h-screen flex flex-col overflow-hidden">

    <!-- HEADER / NAVBAR -->
    <header class="bg-coffee-800 text-white px-6 py-3 flex items-center justify-between shadow-md">
        <div class="flex items-center gap-3">
            <div class="bg-sage-600 p-2 rounded-lg">
                <i class="fa-solid font-bold fa-mug-hot text-xl text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Morning Breeze Coffe</h1>
                <p class="text-xs text-coffee-200">Shift Pagi • Barista: Alex</p>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="w-1/3 relative">
            <input type="text" placeholder="Cari menu kopi, pastry..." 
                   class="w-full bg-coffee-900/50 text-white placeholder-coffee-200 text-sm rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-sage-500 border border-coffee-600">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-coffee-200 text-sm"></i>
        </div>

        <!-- RIGHT HEADER (Dine-in / Takeaway Toggle & Order History) -->
        <div class="flex items-center gap-3">
            <!-- HEADER / NAVBAR (Tombol Riwayat Pesanan) -->
         <button onclick="openHistoryModal()" class="bg-sage-600 hover:bg-sage-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition flex items-center gap-2 shadow-sm">
           <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pesanan
         </button>
            <button class="bg-coffee-900 hover:bg-coffee-900/80 text-white text-sm px-3 py-2 rounded-lg border border-coffee-600">
                <i class="fa-solid fa-user-gear"></i>
            </button>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 flex overflow-hidden">

        <!-- LEFT SIDE: MENU & KATEGORI (2/3 Width) -->
        <div class="w-2/3 flex flex-col p-4 border-r border-coffee-200/60 overflow-hidden">
            
           <!-- KATEGORI SELECTION -->
<div id="categoryContainer" class="flex gap-2 overflow-x-auto pb-3 mb-2 scrollbar-none">
    <button data-category-id="all" class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap bg-coffee-800 text-white shadow-sm transition">
        ☕ Semua Menu
    </button>
    
    @foreach($categories as $category)
        <button data-category-id="{{ $category->id }}" class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap bg-white text-coffee-800 border border-coffee-200 hover:bg-coffee-100 transition">
            {{ $category->name }}
        </button>
    @endforeach
</div>

            <!-- PRODUCT GRID -->
<div class="flex-1 overflow-y-auto pr-1 grid grid-cols-3 gap-4 auto-rows-max">
    @foreach($products as $product)
        <div class="product-card bg-white p-3 rounded-2xl shadow-sm border border-coffee-100 hover:shadow-md hover:border-sage-500 transition cursor-pointer flex flex-col justify-between group" data-category="{{ $product->category_id }}">
            <div>
                <div class="h-28 bg-coffee-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden relative">
                    <i class="fa-solid fa-mug-saucer text-4xl text-coffee-600"></i>
                    @if($product->is_best_seller ?? false)
                        <span class="absolute top-2 right-2 bg-sage-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Best Seller</span>
                    @endif
                </div>
                <h3 class="font-bold text-coffee-900 group-hover:text-sage-700 transition">{{ $product->name }}</h3>
                <p class="text-xs text-gray-500">{{ $product->description ?? 'Freshly brewed' }}</p>
            </div>
            <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100">
                <span class="font-bold text-coffee-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <button onclick="addToCart({{ $product->id }})" class="w-8 h-8 rounded-full bg-sage-100 text-sage-700 flex items-center justify-center font-bold hover:bg-sage-600 hover:text-white transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>
        </div>

        <!-- RIGHT SIDE: CART & CHECKOUT (1/3 Width) -->
        <div class="w-1/3 bg-white flex flex-col h-full shadow-lg">
            
            <!-- CART HEADER -->
            <div class="p-4 border-b border-coffee-100 flex justify-between items-center bg-coffee-50">
                <div>
    <h2 class="font-bold text-coffee-900">Pesanan Saat Ini</h2>
    <p id="currentOrderNumber" class="text-xs text-gray-500">Order #{{ $nextOrderNumber }}</p>
</div>
                <!-- Mode Pesanan -->
                <div class="flex bg-coffee-200/50 p-1 rounded-xl text-xs font-semibold">
                    <button class="px-3 py-1.5 rounded-lg bg-coffee-800 text-white shadow-sm">Dine In</button>
                    <button class="px-3 py-1.5 rounded-lg text-coffee-800 hover:text-coffee-900">Takeaway</button>
                </div>
            </div>

           <!-- CART ITEMS LIST -->
            <div id="cartItemsContainer" class="flex-1 overflow-y-auto p-4 space-y-3">
                <!-- Data keranjang akan masuk secara dinamis lewat JavaScript di sini -->
                <div class="text-center text-gray-400 py-10 text-xs">
                    <i class="fa-solid fa-basket-shopping text-2xl mb-2"></i>
                    <p>Pesanan masih kosong</p>
                </div>
            </div>

            <!-- PAYMENT SUMMARY & ACTION -->
            <div class="p-4 border-t border-coffee-100 bg-coffee-50 space-y-3">
                <div class="space-y-1.5 text-xs text-coffee-900">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span id="subtotalText" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pajak (10%)</span>
                        <span id="taxText" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-coffee-200 text-sm font-bold text-coffee-900">
                        <span>Total Pembayaran</span>
                        <span id="grandTotalText" class="text-base text-coffee-800">Rp 0</span>
                    </div>
                </div>

                <!-- METODE PEMBAYARAN FAST-SELECT (Hanya Tunai & QRIS) -->
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <button class="py-2 border border-coffee-200 rounded-lg bg-white text-xs font-bold text-coffee-800 flex flex-col items-center gap-1 hover:border-sage-500 transition">
                        <i class="fa-solid fa-money-bill-wave text-sage-600 text-sm"></i> Tunai
                    </button>
                    <button id="btnQris" class="py-2 border border-coffee-200 rounded-lg bg-white text-xs font-bold text-coffee-800 flex flex-col items-center gap-1 hover:border-sage-500 transition">
                        <i class="fa-solid fa-qrcode text-sage-600 text-sm"></i> QRIS
                    </button>
                </div>

                <!-- TOMBOL BAYAR UTAMA -->
                <button class="w-full bg-sage-600 hover:bg-sage-700 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i> Proses & Cetak Struk
                </button>
            </div>

        </div>

    </div>
<!-- SCRIPT INTERAKTIF TOMBOL & KERANJANG POS -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data Produk Katalog dari Database Laravel
        const products = @json($products);
        let cart = [];

        // Fungsi Render / Update Tampilan Keranjang
        function updateCartUI() {
            const container = document.getElementById('cartItemsContainer');
            const subtotalEl = document.getElementById('subtotalText');
            const taxEl = document.getElementById('taxText');
            const grandTotalEl = document.getElementById('grandTotalText');
            
            if (!container) return;

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-10 text-xs">
                        <i class="fa-solid fa-basket-shopping text-2xl mb-2"></i>
                        <p>Pesanan masih kosong</p>
                    </div>
                `;
                subtotalEl.innerText = "Rp 0";
                taxEl.innerText = "Rp 0";
                grandTotalEl.innerText = "Rp 0";
                return;
            }

            container.innerHTML = '';
            let subtotal = 0;

            cart.forEach(item => {
                let itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                container.innerHTML += `
                    <div class="p-3 rounded-xl bg-coffee-50 border border-coffee-100 flex flex-col gap-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-sm text-coffee-900">${item.name}</h4>
                                <span class="text-xs text-sage-700 font-medium">${item.description || item.desc || 'Freshly brewed'}</span>
                            </div>
                            <span class="font-bold text-sm text-coffee-800">Rp ${itemTotal.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <button onclick="removeItem(${item.id})" class="text-xs text-red-500 hover:underline"><i class="fa-regular fa-trash-can"></i> Hapus</button>
                            <div class="flex items-center gap-2">
                                <button onclick="decreaseQty(${item.id})" class="w-6 h-6 rounded-lg bg-white border border-coffee-200 text-coffee-800 flex items-center justify-center font-bold text-xs">-</button>
                                <span class="text-xs font-bold w-4 text-center">${item.quantity}</span>
                                <button onclick="increaseQty(${item.id})" class="w-6 h-6 rounded-lg bg-coffee-800 text-white flex items-center justify-center font-bold text-xs">+</button>
                            </div>
                        </div>
                    </div>
                `;
            });

            let tax = subtotal * 0.10;
            let grandTotal = subtotal + tax;

            subtotalEl.innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
            taxEl.innerText = `Rp ${tax.toLocaleString('id-ID')}`;
            grandTotalEl.innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
        }

        // Fungsi Tambah Produk ke Keranjang
        window.addToCart = function(productId) {
            let product = products.find(p => p.id === productId);
            if (!product) return;

            let existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            updateCartUI();
        };

        // Fungsi Tambah Qty di Keranjang
        window.increaseQty = function(productId) {
            let item = cart.find(i => i.id === productId);
            if (item) {
                item.quantity += 1;
                updateCartUI();
            }
        };

        // Fungsi Kurang Qty di Keranjang
        window.decreaseQty = function(productId) {
            let item = cart.find(i => i.id === productId);
            if (item) {
                item.quantity -= 1;
                if (item.quantity <= 0) {
                    cart = cart.filter(i => i.id !== productId);
                }
                updateCartUI();
            }
        };

        // Fungsi Hapus Item
        window.removeItem = function(productId) {
            cart = cart.filter(i => i.id !== productId);
            updateCartUI();
        };

        // Tangkap klik pada tombol Proses & Cetak Struk
        const processBtn = document.querySelector('button.bg-sage-600.hover\\:bg-sage-700.text-white.font-bold');
        if (processBtn) {
            processBtn.addEventListener('click', function() {
                if (cart.length === 0) {
                    alert('Keranjang masih kosong, pilih menu terlebih dahulu!');
                    return;
                }

                // Ambil tipe pesanan aktif (Dine In / Takeaway)
                let activeModeBtn = document.querySelector('.flex.bg-coffee-200\\/50 button.bg-coffee-800');
                let orderType = activeModeBtn ? activeModeBtn.innerText.trim() : 'Dine In';

                // Cek apakah metode QRIS yang aktif
                const qrisBtn = document.getElementById('btnQris');
                let isQrisActive = qrisBtn && qrisBtn.classList.contains('bg-coffee-900');
                let paymentMethod = isQrisActive ? 'QRIS' : 'Tunai';

                if (isQrisActive) {
                    let grandTotalText = document.getElementById('grandTotalText').innerText;
                    document.getElementById('modalTotalAmount').innerText = grandTotalText;
                    document.getElementById('qrisModal').classList.remove('hidden');
                    document.getElementById('qrisModal').classList.add('flex');
                } else {
                    // Kirim data pesanan ke backend Laravel untuk disimpan ke database
                    processCheckout(orderType, paymentMethod);
                }
            });
        }

        // Fungsi Kirim Data Checkout ke Database (Tunai)
        function processCheckout(orderType, paymentMethod) {
            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            let tax = subtotal * 0.10;
            let grandTotal = subtotal + tax;

            let orderData = {
                order_type: orderType,
                payment_method: paymentMethod,
                subtotal: subtotal,
                tax: tax,
                grand_total: grandTotal,
                items: cart.map(item => ({
                    product_id: item.id,
                    product_name: item.name,
                    price: item.price,
                    quantity: item.quantity
                }))
            };

            fetch('/cashier/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                alert(`Transaksi berhasil diproses dengan metode: ${paymentMethod}! Struk sedang dicetak.`);
                cart = [];
                updateCartUI();
                
                // Refresh halaman agar nomor order harian dan riwayat langsung sinkron dengan database
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses transaksi.');
            });
        }

        // Global Click Listener untuk Tombol Toggle (Dine In & Pembayaran)
        document.addEventListener("click", function(e) {
            let btn = e.target.closest('button');
            if (!btn) return;

            // 1. Logika Toggle Dine In / Takeaway
            let modeContainer = btn.closest('.flex.bg-coffee-200\\/50');
            if (modeContainer) {
                let modeButtons = modeContainer.querySelectorAll('button');
                modeButtons.forEach(b => {
                    b.classList.remove('bg-coffee-800', 'text-white', 'shadow-sm', 'hover:text-white');
                    b.classList.add('text-coffee-800', 'hover:text-coffee-900');
                });
                btn.classList.remove('text-coffee-800', 'hover:text-coffee-900');
                btn.classList.add('bg-coffee-800', 'text-white', 'shadow-sm', 'hover:text-white');
                return;
            }

            // 2. Logika Toggle Metode Pembayaran (Diubah ke grid-cols-2)
            let paymentGrid = btn.closest('.grid.grid-cols-2');
            if (paymentGrid) {
                let payButtons = paymentGrid.querySelectorAll('button');
                payButtons.forEach(b => {
                    b.classList.remove('bg-coffee-900', 'text-white', 'border-coffee-900', 'shadow');
                    b.classList.add('bg-white', 'text-coffee-800', 'border-coffee-200');
                    
                    let icon = b.querySelector('i');
                    if (icon) {
                        icon.classList.remove('text-white');
                        icon.classList.add('text-sage-600');
                    }
                });

                btn.classList.remove('bg-white', 'text-coffee-800', 'border-coffee-200');
                btn.classList.add('bg-coffee-900', 'text-white', 'border-coffee-900', 'shadow');
                
                let activeIcon = btn.querySelector('i');
                if (activeIcon) {
                    activeIcon.classList.remove('text-sage-600');
                    activeIcon.classList.add('text-white');
                }
            }
        });
    }); // <-- Batas akhir DOMContentLoaded yang benar

    // Fungsi global di luar DOMContentLoaded agar bisa diakses elemen HTML
    window.closeQrisModal = function() {
        document.getElementById('qrisModal').classList.remove('flex');
        document.getElementById('qrisModal').classList.add('hidden');
    };

    window.finishPayment = function() {
        // Ambil tipe pesanan aktif
        let activeModeBtn = document.querySelector('.flex.bg-coffee-200\\/50 button.bg-coffee-800');
        let orderType = activeModeBtn ? activeModeBtn.innerText.trim() : 'Dine In';

        let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        let tax = subtotal * 0.10;
        let grandTotal = subtotal + tax;

        let orderData = {
            order_type: orderType,
            payment_method: 'QRIS',
            subtotal: subtotal,
            tax: tax,
            grand_total: grandTotal,
            items: cart.map(item => ({
                product_id: item.id,
                product_name: item.name,
                price: item.price,
                quantity: item.quantity
            }))
        };

        // Kirim data transaksi QRIS ke backend Laravel
        fetch('/cashier/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            alert('Pembayaran QRIS berhasil dikonfirmasi! Struk sedang dicetak.');
            closeQrisModal();
            cart = [];
            updateCartUI();

            // Update nomor order otomatis bertambah
            let orderNumEl = document.getElementById('currentOrderNumber');
            if (orderNumEl) {
                let currentText = orderNumEl.innerText;
                let currentNum = parseInt(currentText.replace('Order #', '')) || 1;
                orderNumEl.innerText = 'Order #' + (currentNum + 1);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses QRIS.');
        });
    };

    // Filter Produk Berdasarkan Kategori
    const categoryContainer = document.getElementById('categoryContainer');
    if (categoryContainer) {
        categoryContainer.addEventListener('click', function(e) {
            let catBtn = e.target.closest('button');
            if (!catBtn) return;

            let catButtons = categoryContainer.querySelectorAll('button');
            catButtons.forEach(b => {
                b.classList.remove('bg-coffee-800', 'text-white', 'shadow-sm');
                b.classList.add('bg-white', 'text-coffee-800', 'border', 'border-coffee-200', 'hover:bg-coffee-100');
            });
            catBtn.classList.remove('bg-white', 'text-coffee-800', 'border', 'border-coffee-200', 'hover:bg-coffee-100');
            catBtn.classList.add('bg-coffee-800', 'text-white', 'shadow-sm');

            let selectedCatId = catBtn.getAttribute('data-category-id');
            let productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                let cardCatId = card.getAttribute('data-category');
                if (selectedCatId === 'all' || cardCatId === selectedCatId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Fungsi Membuka Modal Riwayat Pesanan
    window.openHistoryModal = function() {
        const modal = document.getElementById('historyModal');
        const container = document.getElementById('historyListContainer');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        container.innerHTML = `
            <div class="text-center text-gray-400 py-10 text-xs">
                <i class="fa-solid fa-spinner animate-spin text-2xl mb-2"></i>
                <p>Memuat data riwayat...</p>
            </div>
        `;

        fetch('/cashier/history')
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center text-gray-400 py-10 text-xs">
                            <i class="fa-solid fa-receipt text-3xl mb-2"></i>
                            <p>Belum ada riwayat transaksi.</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = '';
                data.forEach(order => {
                    let itemsHtml = order.items.map(item => `
                        <div class="flex justify-between text-xs text-gray-600 py-0.5">
                            <span>• ${item.product_name} <span class="font-semibold text-coffee-900">(x${item.quantity})</span></span>
                            <span>Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                        </div>
                    `).join('');

                    container.innerHTML += `
                        <div class="p-3.5 rounded-xl bg-coffee-50 border border-coffee-100 flex flex-col gap-2.5">
                            <div class="flex justify-between items-center border-b border-coffee-200/50 pb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-coffee-900">${order.order_number || 'Order #' + order.id}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold ${order.order_type === 'Dine In' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'}">
                                        ${order.order_type || 'Dine In'}
                                    </span>
                                </div>
                                <span class="text-xs font-semibold text-sage-700 bg-white px-2 py-1 rounded-md border border-coffee-200">
                                    <i class="fa-solid fa-wallet mr-1"></i> ${order.payment_method || 'Tunai'}
                                </span>
                            </div>
                            <div class="space-y-1">
                                ${itemsHtml}
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-coffee-200/50 text-xs font-bold text-coffee-900">
                                <span class="text-gray-500 font-normal text-[11px]">${order.created_at ? new Date(order.created_at).toLocaleString('id-ID') : ''}</span>
                                <span>Total: <span class="text-coffee-800 text-sm">Rp ${Number(order.grand_total || order.total_amount).toLocaleString('id-ID')}</span></span>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = `
                    <div class="text-center text-red-400 py-10 text-xs">
                        <i class="fa-solid fa-triangle-exclamation text-2xl mb-2"></i>
                        <p>Gagal memuat data riwayat pesanan.</p>
                    </div>
                `;
            });
    };

    window.closeHistoryModal = function() {
        document.getElementById('historyModal').classList.remove('flex');
        document.getElementById('historyModal').classList.add('hidden');
    };
    </script>

    <!-- MODAL POPUP QRIS / PEMBAYARAN -->
    <div id="qrisModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-96 text-center space-y-4">
            <div class="flex justify-between items-center border-b pb-2">
                <h3 class="font-bold text-coffee-900 text-base">Scan QRIS Pembayaran</h3>
                <button onclick="closeQrisModal()" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
            </div>
            <p class="text-xs text-gray-500">Silakan scan menggunakan DANA, GoPay, OVO, atau Mobile Banking pilihan Anda.</p>
            
            <!-- Gambar QR Code dengan Background Gelap agar Kontras dan Tidak Hilang -->
<div class="bg-slate-900 p-4 rounded-xl flex justify-center shadow-md">
    <img src="{{ asset('images/qris-sample.png') }}" alt="QRIS Code" class="w-48 h-48 object-contain rounded-lg">
</div>

            <div class="text-sm font-bold text-coffee-900">
                Total Tagihan: <span id="modalTotalAmount" class="text-coffee-800 text-base">Rp 0</span>
            </div>

            <div class="flex gap-2 pt-2">
                <button onclick="closeQrisModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-coffee-900 text-xs font-bold py-2.5 rounded-xl transition">
                    Batal
                </button>
                <button onclick="finishPayment()" class="flex-1 bg-sage-600 hover:bg-sage-700 text-white text-xs font-bold py-2.5 rounded-xl transition">
                    Pembayaran Selesai & Cetak
                </button>
            </div>
        </div>
    </div>
    <!-- MODAL RIWAYAT PESANAN -->
    <div id="historyModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-[600px] max-h-[85vh] flex flex-col space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <div class="flex items-center gap-2">
                    <div class="bg-sage-100 p-2 rounded-lg text-sage-700">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3 class="font-bold text-coffee-900 text-lg">Riwayat Transaksi Terakhir</h3>
                </div>
                <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <!-- CONTAINER DAFTAR RIWAYAT -->
            <div id="historyListContainer" class="overflow-y-auto flex-1 space-y-3 pr-1">
                <div class="text-center text-gray-400 py-10 text-xs">
                    <i class="fa-solid fa-spinner animate-spin text-2xl mb-2"></i>
                    <p>Memuat riwayat pesanan...</p>
                </div>
            </div>

            <div class="pt-2 border-t">
                <button onclick="closeHistoryModal()" class="w-full bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-bold py-3 rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</body>
</html>
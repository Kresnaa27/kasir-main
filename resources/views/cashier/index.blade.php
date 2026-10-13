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
                <h1 class="font-bold text-lg leading-tight">Bean & Brew Pos</h1>
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
            <button class="bg-sage-600 hover:bg-sage-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
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
            <div class="flex gap-2 overflow-x-auto pb-3 mb-2 scrollbar-none">
                <button class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap bg-coffee-800 text-white shadow-sm">
                    ☕ Semua Menu
                </button>
                <button class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap bg-white text-coffee-800 border border-coffee-200 hover:bg-coffee-100 transition">
                    Espresso Based
                </button>
                <button class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap bg-white text-coffee-800 border border-coffee-200 hover:bg-coffee-100 transition">
                    Manual Brew
                </button>
                <button class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap bg-white text-coffee-800 border border-coffee-200 hover:bg-coffee-100 transition">
                    Non-Coffee
                </button>
                <button class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap bg-white text-coffee-800 border border-coffee-200 hover:bg-coffee-100 transition">
                    Pastry & Bakery
                </button>
            </div>

            <!-- PRODUCT GRID -->
            <div class="flex-1 overflow-y-auto pr-1 grid grid-cols-3 gap-4 auto-rows-max">
                
                <!-- Item 1 -->
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-coffee-100 hover:shadow-md hover:border-sage-500 transition cursor-pointer flex flex-col justify-between group">
                    <div>
                        <div class="h-28 bg-coffee-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden relative">
                            <i class="fa-solid fa-mug-saucer text-4xl text-coffee-600"></i>
                            <span class="absolute top-2 right-2 bg-sage-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Best Seller</span>
                        </div>
                        <h3 class="font-bold text-coffee-900 group-hover:text-sage-700 transition">Caffe Latte</h3>
                        <p class="text-xs text-gray-500">Espresso + Steamed Milk</p>
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100">
                        <span class="font-bold text-coffee-800">Rp 28.000</span>
                        <button onclick="addToCart(1)" class="w-8 h-8 rounded-full bg-sage-100 text-sage-700 flex items-center justify-center font-bold hover:bg-sage-600 hover:text-white transition">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-coffee-100 hover:shadow-md hover:border-sage-500 transition cursor-pointer flex flex-col justify-between group">
                    <div>
                        <div class="h-28 bg-coffee-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-glass-water text-4xl text-coffee-600"></i>
                        </div>
                        <h3 class="font-bold text-coffee-900 group-hover:text-sage-700 transition">Iced Americano</h3>
                        <p class="text-xs text-gray-500">Double Shot + Mineral Water</p>
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100">
                        <span class="font-bold text-coffee-800">Rp 22.000</span>
                        <button onclick="addToCart(2)" class="w-8 h-8 rounded-full bg-sage-100 text-sage-700 flex items-center justify-center font-bold hover:bg-sage-600 hover:text-white transition">
    <i class="fa-solid fa-plus text-xs"></i>
</button>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-coffee-100 hover:shadow-md hover:border-sage-500 transition cursor-pointer flex flex-col justify-between group">
                    <div>
                        <div class="h-28 bg-coffee-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-bread-slice text-4xl text-coffee-600"></i>
                        </div>
                        <h3 class="font-bold text-coffee-900 group-hover:text-sage-700 transition">Butter Croissant</h3>
                        <p class="text-xs text-gray-500">Freshly Baked</p>
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100">
                        <span class="font-bold text-coffee-800">Rp 25.000</span>
                        <button onclick="addToCart(3)" class="w-8 h-8 rounded-full bg-sage-100 text-sage-700 flex items-center justify-center font-bold hover:bg-sage-600 hover:text-white transition">
    <i class="fa-solid fa-plus text-xs"></i>
</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- RIGHT SIDE: CART & CHECKOUT (1/3 Width) -->
        <div class="w-1/3 bg-white flex flex-col h-full shadow-lg">
            
            <!-- CART HEADER -->
            <div class="p-4 border-b border-coffee-100 flex justify-between items-center bg-coffee-50">
                <div>
                    <h2 class="font-bold text-coffee-900">Pesanan Saat Ini</h2>
                    <p class="text-xs text-gray-500">Order #1042</p>
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

                <!-- METODE PEMBAYARAN FAST-SELECT -->
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <button class="py-2 border border-coffee-200 rounded-lg bg-white text-xs font-bold text-coffee-800 flex flex-col items-center gap-1 hover:border-sage-500">
                        <i class="fa-solid fa-money-bill-wave text-sage-600"></i> Tunai
                    </button>
                    <button class="py-2 border border-coffee-200 rounded-lg bg-white text-xs font-bold text-coffee-800 flex flex-col items-center gap-1 hover:border-sage-500">
                        <i class="fa-solid fa-qrcode text-sage-600"></i> QRIS
                    </button>
                    <button class="py-2 border border-coffee-200 rounded-lg bg-white text-xs font-bold text-coffee-800 flex flex-col items-center gap-1 hover:border-sage-500">
                        <i class="fa-regular fa-credit-card text-sage-600"></i> Debit/Kredit
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
        // Data Produk Katalog (Bisa disesuaikan dengan data database Blade nantinya)
        const products = [
            { id: 1, name: "Caffe Latte", price: 28000, desc: "Espresso + Steamed Milk" },
            { id: 2, name: "Iced Americano", price: 22000, desc: "Double Shot + Mineral Water" },
            { id: 3, name: "Butter Croissant", price: 25000, desc: "Freshly Baked" }
        ];

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
                                <span class="text-xs text-sage-700 font-medium">${item.desc}</span>
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

        // Fungsi Tambah Produk ke Keranjang (Dipicu dari tombol plus di produk)
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

        // Hubungkan tombol plus (+) di setiap kartu produk katalog agar interaktif
        // Berikan atribut onclick="addToCart(1)" dsb pada tombol plus di HTML produk katalogmu
        
        // -------------------------------------------------------------
        // Global Click Listener untuk Tombol Toggle (Dine In & Pembayaran)
        // -------------------------------------------------------------
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

            // 2. Logika Toggle Metode Pembayaran (Tunai, QRIS, Debit/Kredit)
            let paymentGrid = btn.closest('.grid.grid-cols-3');
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
    });
    </script>
</body>
</html>
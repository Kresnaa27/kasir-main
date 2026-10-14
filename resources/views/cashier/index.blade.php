<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Coffee Shop - Bean & Brew</title>
    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css'])
    <!-- Alpine.js CDN agar interaktivitas tombol 100% langsung berfungsi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.userQrCodeData = "{{ Auth::user()->qr_code ?? 'QRIS-DEFAULT-MERCHANT' }}";
    </script>
</head>
<body class="bg-[#FDFBF7] font-sans antialiased text-[#2C1A11] h-screen flex flex-col overflow-hidden" x-data="posApp()">

    <!-- HEADER / NAVBAR -->
    <header class="bg-[#4A2E1B] text-white px-6 py-3 flex items-center justify-between shadow-md relative z-20">
        <div class="flex items-center gap-3">
            <div class="bg-[#6B8E78] p-2 rounded-lg">
                <i class="fa-solid fa-mug-hot text-xl text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Bean & Brew Pos</h1>
                <p class="text-xs text-[#E6D2C1]">Barista: {{ Auth::user()->name }}</p>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <form action="{{ route('cashier.index') }}" method="GET" class="w-1/3 relative">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu coffee, drink, food, desserts..." 
                   class="w-full bg-[#2C1A11]/50 text-white placeholder-[#E6D2C1] text-sm rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-[#6B8E78] border border-[#6B8E78]/50">
            <button type="submit" class="absolute left-3.5 top-2.5 text-[#E6D2C1] text-sm bg-transparent border-none cursor-pointer">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <!-- RIGHT HEADER: USER DROPDOWN & LOGOUT -->
        <div class="flex items-center gap-3">
            <button class="bg-[#6B8E78] hover:bg-[#52715E] text-white text-sm px-4 py-2 rounded-lg font-medium transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pesanan
            </button>

            <!-- DROPDOWN USER -->
            <div class="relative" x-data="{ openUserMenu: false }">
                <button @click="openUserMenu = !openUserMenu" class="bg-[#2C1A11] hover:bg-[#2C1A11]/80 text-white text-sm px-3 py-2 rounded-lg border border-[#6B8E78] flex items-center gap-2">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="text-xs font-semibold">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>

                <div x-show="openUserMenu" @click.away="openUserMenu = false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 text-sm text-gray-700 z-50" style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-xs text-gray-400">Akun Login:</p>
                        <p class="font-bold text-[#4A2E1B] truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 flex items-center gap-2 font-medium">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 flex overflow-hidden">

        <!-- LEFT SIDE: MENU & KATEGORI (2/3 Width) -->
        <div class="w-2/3 flex flex-col p-4 border-r border-gray-200 overflow-hidden">
            
            <!-- KATEGORI SELECTION -->
            <div class="flex gap-2 overflow-x-auto pb-3 mb-2">
                <a href="{{ route('cashier.index', ['search' => request('search')]) }}" 
                   class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap {{ request('category') == '' ? 'bg-[#4A2E1B] text-white shadow-sm' : 'bg-white text-[#4A2E1B] border border-gray-200 hover:bg-[#F4EAE1]' }}">
                    ☕ Semua Menu
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('cashier.index', ['category' => $cat->id, 'search' => request('search')]) }}" 
                       class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap {{ request('category') == $cat->id ? 'bg-[#4A2E1B] text-white shadow-sm' : 'bg-white text-[#4A2E1B] border border-gray-200 hover:bg-[#F4EAE1]' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- PRODUCT GRID -->
            <div class="flex-1 overflow-y-auto pr-1 grid grid-cols-3 gap-4 auto-rows-max">
                @forelse($products as $product)
                    <div @click="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, description: '{{ addslashes($product->description) }}' })"
                         class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-[#6B8E78] transition cursor-pointer flex flex-col justify-between group">
                        <div>
                            <div class="h-28 bg-[#F4EAE1] rounded-xl mb-3 flex items-center justify-center relative overflow-hidden">
                                <i class="fa-solid fa-mug-saucer text-3xl text-[#8C5A3C]"></i>
                                @if($product->is_best_seller)
                                    <span class="absolute top-2 right-2 bg-[#6B8E78] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">Best Seller</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-[#2C1A11] group-hover:text-[#6B8E78] transition">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-1">{{ $product->description }}</p>
                        </div>
                        <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100">
                            <span class="font-bold text-[#4A2E1B]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="w-8 h-8 rounded-full bg-[#E8F0EC] text-[#52715E] flex items-center justify-center font-bold group-hover:bg-[#6B8E78] group-hover:text-white transition">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-400">
                        <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                        <p>Tidak ada produk yang ditemukan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT SIDE: CART & CHECKOUT (1/3 Width) -->
        <div class="w-1/3 bg-white flex flex-col h-full shadow-lg">
            
            <!-- CART HEADER -->
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-[#FDFBF7]">
                <div>
                    <h2 class="font-bold text-[#2C1A11]">Pesanan Saat Ini</h2>
                    <p class="text-xs text-gray-500">Kasir: {{ Auth::user()->name }}</p>
                </div>
                <!-- Mode Pesanan Dine In / Takeaway -->
                <div class="flex bg-[#E6D2C1]/40 p-1 rounded-xl text-xs font-semibold cursor-pointer">
                    <button type="button" @click="orderType = 'Dine In'" :class="orderType === 'Dine In' ? 'bg-[#4A2E1B] text-white shadow-sm' : 'text-[#4A2E1B]'" class="px-3 py-1.5 rounded-lg transition">Dine In</button>
                    <button type="button" @click="orderType = 'Takeaway'" :class="orderType === 'Takeaway' ? 'bg-[#4A2E1B] text-white shadow-sm' : 'text-[#4A2E1B]'" class="px-3 py-1.5 rounded-lg transition">Takeaway</button>
                </div>
            </div>

            <!-- CART ITEMS LIST -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="p-3 rounded-xl bg-[#FDFBF7] border border-gray-100 flex flex-col gap-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-sm text-[#2C1A11]" x-text="item.name"></h4>
                                <span class="text-xs text-[#6B8E78]" x-text="'Rp ' + Number(item.price).toLocaleString()"></span>
                            </div>
                            <span class="font-bold text-sm text-[#4A2E1B]" x-text="'Rp ' + (item.price * item.quantity).toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <button type="button" @click="removeFromCart(index)" class="text-xs text-red-500 hover:underline"><i class="fa-regular fa-trash-can"></i> Hapus</button>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="updateQty(index, -1)" class="w-6 h-6 rounded-lg bg-white border border-gray-200 text-[#2C1A11] flex items-center justify-center font-bold text-xs">-</button>
                                <span class="text-xs font-bold w-4 text-center" x-text="item.quantity"></span>
                                <button type="button" @click="updateQty(index, 1)" class="w-6 h-6 rounded-lg bg-[#4A2E1B] text-white flex items-center justify-center font-bold text-xs">+</button>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="text-center text-gray-400 py-12 text-xs">
                    <i class="fa-solid fa-basket-shopping text-3xl mb-2"></i>
                    <p>Pesanan masih kosong</p>
                </div>
            </div>

            <!-- PAYMENT SUMMARY & ACTION -->
            <div class="p-4 border-t border-gray-100 bg-[#FDFBF7] space-y-3">
                <div class="space-y-1.5 text-xs text-[#2C1A11]">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-semibold" x-text="'Rp ' + subtotal.toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pajak (10%)</span>
                        <span class="font-semibold" x-text="'Rp ' + tax.toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200 text-sm font-bold text-[#2C1A11]">
                        <span>Total Pembayaran</span>
                        <span class="text-base text-[#4A2E1B]" x-text="'Rp ' + total.toLocaleString()"></span>
                    </div>
                </div>

                <!-- METODE PEMBAYARAN -->
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <button type="button" @click="paymentMethod = 'Tunai'" :class="paymentMethod === 'Tunai' ? 'border-[#6B8E78] bg-[#E8F0EC] text-[#4A2E1B] ring-1 ring-[#6B8E78]' : 'bg-white text-gray-700 border-gray-200'" class="py-2 border rounded-lg text-xs font-bold flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-money-bill-wave text-[#6B8E78]"></i> Tunai
                    </button>
                    <button type="button" @click="paymentMethod = 'QRIS'" :class="paymentMethod === 'QRIS' ? 'border-[#6B8E78] bg-[#E8F0EC] text-[#4A2E1B] ring-1 ring-[#6B8E78]' : 'bg-white text-gray-700 border-gray-200'" class="py-2 border rounded-lg text-xs font-bold flex flex-col items-center gap-1 transition">
                        <i class="fa-solid fa-qrcode text-[#6B8E78]"></i> QRIS
                    </button>
                    <button type="button" @click="paymentMethod = 'Debit/Kredit'" :class="paymentMethod === 'Debit/Kredit' ? 'border-[#6B8E78] bg-[#E8F0EC] text-[#4A2E1B] ring-1 ring-[#6B8E78]' : 'bg-white text-gray-700 border-gray-200'" class="py-2 border rounded-lg text-xs font-bold flex flex-col items-center gap-1 transition">
                        <i class="fa-regular fa-credit-card text-[#6B8E78]"></i> Kartu
                    </button>
                </div>

                <!-- TOMBOL BAYAR UTAMA -->
                <button type="button" @click="processPayment()" class="w-full bg-[#6B8E78] hover:bg-[#52715E] text-white font-bold py-3.5 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-print"></i> Proses Pembayaran
                </button>
            </div>

        </div>

    </div>

    <!-- MODAL QRIS UNIK USER -->
    <div x-show="showQrisModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center space-y-4 shadow-xl relative">
            <h3 class="font-bold text-lg text-[#4A2E1B]">Scan QRIS Pembayaran</h3>
            <p class="text-xs text-gray-500">Merchant QRIS Kasir: <span class="font-semibold text-[#6B8E78]">{{ Auth::user()->name }}</span></p>
            
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex justify-center">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(userQrCode + '-TOTAL-' + total)" alt="QRIS Code" class="rounded-lg shadow-sm">
            </div>

            <div class="bg-[#FDFBF7] p-3 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-500">Total Tagihan:</p>
                <p class="text-lg font-bold text-[#4A2E1B]" x-text="'Rp ' + total.toLocaleString()"></p>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" @click="showQrisModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-xl text-xs transition">Batal</button>
                <button type="button" @click="submitCheckout()" class="flex-1 bg-[#6B8E78] hover:bg-[#52715E] text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm">Sudah Bayar & Simpan</button>
            </div>
        </div>
    </div>

    <!-- SKRIP LOGIKA ALPINE.JS POS -->
    <script>
        function posApp() {
            return {
                cart: [],
                orderType: 'Dine In',
                paymentMethod: 'Tunai',
                showQrisModal: false,
                userQrCode: window.userQrCodeData || 'QRIS-DEFAULT',

                addToCart(product) {
                    let existing = this.cart.find(item => item.id === product.id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.cart.push({ 
                            id: product.id, 
                            name: product.name, 
                            price: product.price, 
                            quantity: 1,
                            description: product.description || ''
                        });
                    }
                },
                updateQty(index, amount) {
                    this.cart[index].quantity += amount;
                    if (this.cart[index].quantity <= 0) {
                        this.cart.splice(index, 1);
                    }
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },
                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                get tax() {
                    return this.subtotal * 0.10;
                },
                get total() {
                    return this.subtotal + this.tax;
                },
                processPayment() {
                    if (this.cart.length === 0) {
                        alert('Keranjang masih kosong!');
                        return;
                    }
                    if (this.paymentMethod === 'QRIS') {
                        this.showQrisModal = true;
                    } else {
                        this.submitCheckout();
                    }
                },
                submitCheckout() {
                    fetch('/cashier/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            order_type: this.orderType,
                            payment_method: this.paymentMethod,
                            items: this.cart
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Transaksi Berhasil Disimpan! Nomor Order: ' + data.order.order_number);
                            this.cart = [];
                            this.showQrisModal = false;
                            window.location.reload();
                        } else {
                            alert('Gagal: ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Terjadi kesalahan saat memproses pembayaran.');
                    });
                }
            }
        }
    </script>
</body>
</html>
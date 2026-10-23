<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir Coffee Shop</title>
    
    <!-- Tailwind CSS & Vite Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Oper data produk dari PHP ke JavaScript global agar bisa dibaca app.js
        window.appProducts = @json($products);
        
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coffee: { 50: '#FDFBF7', 100: '#F4EAE1', 200: '#E6D2C1', 600: '#8C5A3C', 800: '#4A2E1B', 900: '#2C1A11' },
                        sage: { 100: '#E8F0EC', 500: '#84A98C', 600: '#6B8E78', 700: '#52715E' }
                    }
                }
            }
        }
    </script>
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

        <div class="w-1/3 relative">
            <input type="text" placeholder="Cari menu kopi, pastry..." 
                   class="w-full bg-coffee-900/50 text-white placeholder-coffee-200 text-sm rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-sage-500 border border-coffee-600">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-coffee-200 text-sm"></i>
        </div>

        <div class="flex items-center gap-3">
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
        <!-- LEFT SIDE: MENU & KATEGORI -->
        <div class="w-2/3 flex flex-col p-4 border-r border-coffee-200/60 overflow-hidden">
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

        <!-- RIGHT SIDE: CART & CHECKOUT -->
        <div class="w-1/3 bg-white flex flex-col h-full shadow-lg">
            <div class="p-4 border-b border-coffee-100 flex justify-between items-center bg-coffee-50">
                <div>
                    <h2 class="font-bold text-coffee-900">Pesanan Saat Ini</h2>
                    <p id="currentOrderNumber" class="text-xs text-gray-500">Order #{{ $nextOrderNumber }}</p>
                </div>
                <div class="flex bg-coffee-200/50 p-1 rounded-xl text-xs font-semibold">
                    <button class="px-3 py-1.5 rounded-lg bg-coffee-800 text-white shadow-sm">Dine In</button>
                    <button class="px-3 py-1.5 rounded-lg text-coffee-800 hover:text-coffee-900">Takeaway</button>
                </div>
            </div>

            <div id="cartItemsContainer" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="text-center text-gray-400 py-10 text-xs">
                    <i class="fa-solid fa-basket-shopping text-2xl mb-2"></i>
                    <p>Pesanan masih kosong</p>
                </div>
            </div>

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

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <button class="py-2 border border-coffee-200 rounded-lg bg-white text-xs font-bold text-coffee-800 flex flex-col items-center gap-1 hover:border-sage-500 transition">
                        <i class="fa-solid fa-money-bill-wave text-sage-600 text-sm"></i> Tunai
                    </button>
                    <button id="btnQris" class="py-2 border border-coffee-200 rounded-lg bg-white text-xs font-bold text-coffee-800 flex flex-col items-center gap-1 hover:border-sage-500 transition">
                        <i class="fa-solid fa-qrcode text-sage-600 text-sm"></i> QRIS
                    </button>
                </div>

                <button class="w-full bg-sage-600 hover:bg-sage-700 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i> Proses & Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL QRIS -->
    <div id="qrisModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-96 text-center space-y-4">
            <div class="flex justify-between items-center border-b pb-2">
                <h3 class="font-bold text-coffee-900 text-base">Scan QRIS Pembayaran</h3>
                <button onclick="closeQrisModal()" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
            </div>
            <p class="text-xs text-gray-500">Silakan scan menggunakan aplikasi pembayaran Anda.</p>
            <div class="bg-slate-900 p-4 rounded-xl flex justify-center shadow-md">
                <img src="{{ asset('images/qris-sample.png') }}" alt="QRIS Code" class="w-48 h-48 object-contain rounded-lg">
            </div>
            <div class="text-sm font-bold text-coffee-900">
                Total Tagihan: <span id="modalTotalAmount" class="text-coffee-800 text-base">Rp 0</span>
            </div>
            <div class="flex gap-2 pt-2">
                <button onclick="closeQrisModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-coffee-900 text-xs font-bold py-2.5 rounded-xl transition">Batal</button>
                <button onclick="finishPayment()" class="flex-1 bg-sage-600 hover:bg-sage-700 text-white text-xs font-bold py-2.5 rounded-xl transition">Selesai & Cetak</button>
            </div>
        </div>
    </div>

    <!-- MODAL RIWAYAT -->
    <div id="historyModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-[600px] max-h-[85vh] flex flex-col space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <div class="flex items-center gap-2">
                    <div class="bg-sage-100 p-2 rounded-lg text-sage-700"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <h3 class="font-bold text-coffee-900 text-lg">Riwayat Transaksi Terakhir</h3>
                </div>
                <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>
            <div id="historyListContainer" class="overflow-y-auto flex-1 space-y-3 pr-1">
                <div class="text-center text-gray-400 py-10 text-xs">
                    <i class="fa-solid fa-spinner animate-spin text-2xl mb-2"></i>
                    <p>Memuat riwayat...</p>
                </div>
            </div>
            <div class="pt-2 border-t">
                <button onclick="closeHistoryModal()" class="w-full bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-bold py-3 rounded-xl transition">Tutup</button>
            </div>
        </div>
    </div>
</body>
</html>
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function() {
    let cart = [];

    // Fungsi Render / Update Tampilan Keranjang
    window.updateCartUI = function() {
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
                            <span class="text-xs text-sage-700 font-medium">${item.description || 'Freshly brewed'}</span>
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
    };

    // Fungsi Tambah Produk ke Keranjang
    window.addToCart = function(productId) {
        let product = window.appProducts.find(p => p.id === productId);
        if (!product) return;

        let existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        updateCartUI();
    };

    window.increaseQty = function(productId) {
        let item = cart.find(i => i.id === productId);
        if (item) {
            item.quantity += 1;
            updateCartUI();
        }
    };

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

    window.removeItem = function(productId) {
        cart = cart.filter(i => i.id !== productId);
        updateCartUI();
    };

    // Tombol Proses & Cetak Struk
    const processBtn = document.querySelector('button.bg-sage-600.hover\\:bg-sage-700.text-white.font-bold');
    if (processBtn) {
        processBtn.addEventListener('click', function() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong, pilih menu terlebih dahulu!');
                return;
            }

            let activeModeBtn = document.querySelector('.flex.bg-coffee-200\\/50 button.bg-coffee-800');
            let orderType = activeModeBtn ? activeModeBtn.innerText.trim() : 'Dine In';

            const qrisBtn = document.getElementById('btnQris');
            let isQrisActive = qrisBtn && qrisBtn.classList.contains('bg-coffee-900');
            let paymentMethod = isQrisActive ? 'QRIS' : 'Tunai';

            if (isQrisActive) {
                let grandTotalText = document.getElementById('grandTotalText').innerText;
                document.getElementById('modalTotalAmount').innerText = grandTotalText;
                document.getElementById('qrisModal').classList.remove('hidden');
                document.getElementById('qrisModal').classList.add('flex');
            } else {
                processCheckout(orderType, paymentMethod);
            }
        });
    }

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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Transaksi berhasil diproses dengan metode: ${paymentMethod}! Struk sedang dicetak.`);
                cart = [];
                updateCartUI();
                location.reload();
            } else {
                alert('Gagal menyimpan transaksi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses transaksi.');
        });
    }

    window.closeQrisModal = function() {
        document.getElementById('qrisModal').classList.remove('flex');
        document.getElementById('qrisModal').classList.add('hidden');
    };

    window.finishPayment = function() {
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

        fetch('/cashier/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pembayaran QRIS berhasil dikonfirmasi! Struk sedang dicetak.');
                closeQrisModal();
                cart = [];
                updateCartUI();
                location.reload();
            } else {
                alert('Gagal menyimpan QRIS: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses QRIS.');
        });
    };

    // Modal Riwayat Pesanan
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
                                    <span class="font-bold text-sm text-coffee-900">${order.order_number}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold ${order.order_type === 'Dine In' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'}">
                                        ${order.order_type}
                                    </span>
                                </div>
                                <span class="text-xs font-semibold text-sage-700 bg-white px-2 py-1 rounded-md border border-coffee-200">
                                    <i class="fa-solid fa-wallet mr-1"></i> ${order.payment_method}
                                </span>
                            </div>
                            <div class="space-y-1">
                                ${itemsHtml}
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-coffee-200/50 text-xs font-bold text-coffee-900">
                                <span class="text-gray-500 font-normal text-[11px]">${new Date(order.created_at).toLocaleString('id-ID')}</span>
                                <span>Total: <span class="text-coffee-800 text-sm">Rp ${Number(order.grand_total).toLocaleString('id-ID')}</span></span>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = `
                    <div class="text-red-400 text-center py-10 text-xs">Gagal memuat riwayat.</div>
                `;
            });
    };

    window.closeHistoryModal = function() {
        document.getElementById('historyModal').classList.remove('flex');
        document.getElementById('historyModal').classList.add('hidden');
    };
    // Logika Toggle Metode Pembayaran (Tunai & QRIS)
    const paymentGrid = document.querySelector('.grid.grid-cols-2');
    if (paymentGrid) {
        paymentGrid.addEventListener('click', function(e) {
            let btn = e.target.closest('button');
            if (!btn) return;

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
        });
    }
    // Logika Toggle Mode Pesanan (Dine In / Takeaway)
    document.addEventListener("click", function(e) {
        let btn = e.target.closest('button');
        if (!btn) return;

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
    });
    // Logika Real-time Search Produk
    const searchInput = document.querySelector('header input[type="text"]');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            let keyword = e.target.value.toLowerCase().trim();
            let productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                let productName = card.querySelector('h3').innerText.toLowerCase();
                let productDesc = card.querySelector('p').innerText.toLowerCase();

                if (productName.includes(keyword) || productDesc.includes(keyword)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    // Logika Filter Produk Berdasarkan Kategori
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
});
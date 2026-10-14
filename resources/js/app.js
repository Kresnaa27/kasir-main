import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('alpine:init', () => {
    Alpine.data('posApp', () => ({
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
    }));
});
import { getCsrfToken, getPageConfig } from '../utils/helpers';

export function cartHandler() {
    const config = getPageConfig('cart-config');

    return {
        selectedCount: 0,
        totalPrice: 0,
        showDeleteModal: false,
        cartIdToDelete: null,
        items: config.items ?? [],
        updateUrl: config.updateUrl ?? '',

        init() {
            this.updateSummary();

            window.addEventListener('update-cart', (e) => {
                this.updateQty(e.detail.id, e.detail.qty);
            });
        },

        updateSummary() {
            const checkboxes = document.querySelectorAll('input[name="cart_ids[]"]:checked');
            this.selectedCount = checkboxes.length;

            let total = 0;
            checkboxes.forEach((cb) => {
                const item = this.items.find((i) => i.id == cb.value);

                if (item) {
                    total += item.price * item.qty;
                }
            });
            this.totalPrice = total;
        },

        async updateQty(cartId, newQty) {
            const item = this.items.find((i) => i.id == cartId);

            if (! item || newQty < 1 || newQty > item.stok) {
                return;
            }

            item.qty = newQty;
            this.updateSummary();

            try {
                const response = await fetch(`${this.updateUrl}/${cartId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({ jumlah: newQty }),
                });

                const data = await response.json();

                if (! data.success) {
                    alert(data.message);
                    location.reload();
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        },

        removeItem(cartId) {
            this.cartIdToDelete = cartId;
            this.showDeleteModal = true;
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
    };
}

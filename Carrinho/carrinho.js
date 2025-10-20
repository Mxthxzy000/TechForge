const products = [
            {
                id: 1,
                name: 'Gabinete Gamer Abacom, Redragon, RGB, Mid-Tower, Lateral de Vidro, Com 3 Fans, Preto, MCB-R06K-RGB03',
                price: 289.90,
                quantity: 1,
                image: 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=200&h=200&fit=crop'
            },
            {
                id: 2,
                name: 'Placa Mãe Gigabyte B550M Aorus Elite, DDR4, Socket AMD AM4, M-ATX, Chipset AMD B550, SSDM2-ELITE',
                price: 679.90,
                quantity: 1,
                image: 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=200&h=200&fit=crop'
            },
            {
                id: 3,
                name: 'Processador AMD Ryzen 5 5500, 6-Core, 12-Threads, 3.6GHz (4.2GHz Turbo), Cache 19MB, AM4, 100-100000457BOX',
                price: 549.90,
                quantity: 1,
                image: 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=200&h=200&fit=crop'
            }
        ];

        let cart = [...products];
        const discountPercent = 0.05; // 5% discount

        function formatPrice(price) {
            return `R$ ${price.toFixed(2).replace('.', ',')}`;
        }

        function updateQuantity(id, change) {
            const item = cart.find(item => item.id === id);
            if (item) {
                item.quantity = Math.max(1, item.quantity + change);
                renderCart();
            }
        }

        function removeItem(id) {
            cart = cart.filter(item => item.id !== id);
            renderCart();
            alert('Item removido do carrinho.');
        }

        function calculateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discount = subtotal * discountPercent;
            const total = subtotal - discount;
            
            return { subtotal, discount, total };
        }

        function renderCart() {
            const cartItemsContainer = document.getElementById('cartItems');
            const { subtotal, discount, total } = calculateTotals();

            // Render cart items
            cartItemsContainer.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <div class="item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="item-details">
                        <div class="item-name">${item.name}</div>
                    </div>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">−</button>
                        <input type="text" class="quantity-input" value="${item.quantity}" readonly>
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                    </div>
                    <div class="item-price">${formatPrice(item.price * item.quantity)}</div>
                    <button class="remove-btn" onclick="removeItem(${item.id})">
                        <svg class="remove-icon" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15" stroke="white" stroke-width="2"/>
                            <line x1="9" y1="9" x2="15" y2="15" stroke="white" stroke-width="2"/>
                        </svg>
                        REMOVER
                    </button>
                </div>
            `).join('');

            // Update summary
            document.getElementById('subtotal').textContent = formatPrice(subtotal);
            document.getElementById('discount').textContent = formatPrice(discount);
            document.getElementById('total').textContent = formatPrice(total);
        }

        // Initial render
        renderCart();
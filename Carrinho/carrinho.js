document.addEventListener("DOMContentLoaded", () => {
  const discountPercent = 0.05 // 5% discount for PIX
  const Swal = window.Swal

  function formatPrice(price) {
    return `R$ ${price.toFixed(2).replace(".", ",")}`
  }

  function updateQuantity(productId, change) {
    fetch("cartAPI.php?action=getCart")
      .then((response) => response.json())
      .then((data) => {
        const item = data.produtos.find((p) => p.idProduto == productId)
        if (item) {
          const newQuantity = Math.max(1, item.quantidade + change)

          fetch("cartAPI.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `action=updateQuantity&idProduto=${productId}&quantidade=${newQuantity}`,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                loadCartItems()
              }
            })
        }
      })
  }

  function removeItem(productId) {
    fetch("cartAPI.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=removeItem&idProduto=${productId}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          if (Swal) {
            Swal.fire({
              icon: "success",
              title: "Item removido do carrinho!",
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
            })
          }
          loadCartItems()
        }
      })
  }

  function calculateTotals(cartItems) {
    const subtotal = cartItems.reduce((sum, item) => {
      return sum + Number.parseFloat(item.precoProduto) * item.quantidade
    }, 0)
    const discount = subtotal * discountPercent
    const total = subtotal - discount

    return { subtotal, discount, total }
  }

  function loadCartItems() {
    const cartItemsContainer = document.getElementById("cartItems")

    fetch("cartAPI.php?action=getCart")
      .then((response) => response.json())
      .then((data) => {
        if (data.needsLogin) {
          window.location.href = "../login/login.php"
          return
        }

        if (data.error) {
          console.error("Erro:", data.error)
          cartItemsContainer.innerHTML = '<p style="color: red;">Erro ao carregar carrinho.</p>'
          return
        }

        const cartItems = data.produtos

        if (cartItems.length === 0) {
          cartItemsContainer.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #666;">
                <ion-icon name="cart-outline" style="font-size: 64px; color: #ccc;"></ion-icon>
                <p style="margin-top: 20px; font-size: 18px;">Seu carrinho está vazio</p>
                <a href="../Catalogo/catalogo.php" style="display: inline-block; margin-top: 20px; padding: 12px 24px; background-color: #f8c10d; color: #1a2a3c; text-decoration: none; border-radius: 8px; font-weight: bold;">
                    Continuar Comprando
                </a>
            </div>
          `

          document.getElementById("subtotal").textContent = "R$ 0,00"
          document.getElementById("discount").textContent = "R$ 0,00"
          document.getElementById("total").textContent = "R$ 0,00"
          return
        }

        renderCart(cartItems)
      })
      .catch((error) => {
        console.error("Erro ao carregar carrinho:", error)
        cartItemsContainer.innerHTML = '<p style="color: red;">Erro ao carregar carrinho.</p>'
      })
  }

  function renderCart(cartItems) {
    const cartItemsContainer = document.getElementById("cartItems")
    const { subtotal, discount, total } = calculateTotals(cartItems)

    cartItemsContainer.innerHTML = cartItems
      .map(
        (item) => `
            <div class="cart-item">
                <div class="item-image">
                    <img src="../imagens_produtos/${item.imagemProduto}" alt="${item.nomeProduto}">
                </div>
                <div class="item-details">
                    <div class="item-name">${item.nomeProduto}</div>
                </div>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity('${item.idProduto}', -1)">−</button>
                    <input type="text" class="quantity-input" value="${item.quantidade}" readonly>
                    <button class="quantity-btn" onclick="updateQuantity('${item.idProduto}', 1)">+</button>
                </div>
                <div class="item-price">${formatPrice(Number.parseFloat(item.precoProduto) * item.quantidade)}</div>
                <button class="remove-btn" onclick="removeItem('${item.idProduto}')">
                    <svg class="remove-icon" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15" stroke="white" stroke-width="2"/>
                        <line x1="9" y1="9" x2="15" y2="15" stroke="white" stroke-width="2"/>
                    </svg>
                    REMOVER
                </button>
            </div>
        `,
      )
      .join("")

    document.getElementById("subtotal").textContent = formatPrice(subtotal)
    document.getElementById("discount").textContent = formatPrice(discount)
    document.getElementById("total").textContent = formatPrice(total)
  }

  window.updateQuantity = updateQuantity
  window.removeItem = removeItem

  const checkoutBtn = document.querySelector(".checkout-btn")
  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", () => {
      fetch("cartAPI.php?action=getCart")
        .then((response) => response.json())
        .then((data) => {
          if (data.produtos.length === 0) {
            if (Swal) {
              Swal.fire({
                icon: "warning",
                title: "Carrinho vazio!",
                text: "Adicione produtos ao carrinho antes de finalizar o pedido.",
              })
            } else {
              alert("Adicione produtos ao carrinho antes de finalizar o pedido.")
            }
            return
          }

          window.location.href = "../Checkout/out.php"
        })
    })
  }

  loadCartItems()
})

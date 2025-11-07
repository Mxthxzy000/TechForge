document.addEventListener("DOMContentLoaded", () => {
  // Add to cart functionality
  const btnAdicionarCarrinho = document.getElementById("btnAdicionarCarrinho")

  if (btnAdicionarCarrinho) {
    btnAdicionarCarrinho.addEventListener("click", function () {
      const productId = this.getAttribute("data-id")

      fetch("../Catalogo/addToCart.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `idProduto=${productId}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.needsLogin) {
            window.showNotification("Faça login para adicionar produtos ao carrinho!", "warning")
            return
          }

          if (data.error) {
            window.showNotification(data.error, "error")
            return
          }

          if (data.message) {
            window.showNotification(data.message, "success")
          }
        })
        .catch((error) => {
          window.showNotification("Erro ao adicionar ao carrinho", "error")
        })
    })
  }

  function updateCartBadge() {
    fetch("../Carrinho/cartAPI.php?action=getCart")
      .then((response) => response.json())
      .then((data) => {
        if (data.needsLogin) return

        const totalItems = data.produtos.reduce((sum, item) => sum + item.quantidade, 0)

        const cartButton = document.getElementById("carrinho")
        if (cartButton) {
          const existingBadge = cartButton.querySelector(".cart-badge")
          if (existingBadge) {
            existingBadge.remove()
          }

          if (totalItems > 0) {
            const badge = document.createElement("span")
            badge.className = "cart-badge"
            badge.textContent = totalItems
            cartButton.style.position = "relative"
            cartButton.appendChild(badge)
          }
        }
      })
      .catch((error) => console.error("Erro ao atualizar badge:", error))
  }

  // Update cart badge on page load
  updateCartBadge()

  // Tag click functionality - redirect to catalog with tag filter
  const tagItems = document.querySelectorAll(".tag-item")
  tagItems.forEach((tag) => {
    tag.addEventListener("click", function () {
      const tagText = this.textContent.trim()
      window.location.href = `../Catalogo/catalogo.php?tag=${encodeURIComponent(tagText)}`
    })
  })
})

document.addEventListener("DOMContentLoaded", () => {
  // Add to cart functionality
  const btnAdicionarCarrinho = document.getElementById("btnAdicionarCarrinho")

  if (btnAdicionarCarrinho) {
    btnAdicionarCarrinho.addEventListener("click", function () {
      const productId = this.getAttribute("data-id")
      addToCart(productId)
    })
  }

  function addToCart(productId) {
    console.log("[v0] Adding product to cart:", productId)

    fetch("../Carrinho/cartAPI.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=addToCart&idProduto=${productId}&quantidade=1`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.needsLogin) {
          showNotification("Faça login para adicionar produtos ao carrinho!", "warning")
          setTimeout(() => {
            window.location.href = "../Login/login.php"
          }, 2000)
          return
        }

        if (data.error) {
          showNotification(data.error, "error")
          return
        }

        if (data.success) {
          showNotification(data.message, "success")
          updateCartBadge()
        }
      })
      .catch((error) => {
        console.error("Erro ao adicionar ao carrinho:", error)
        showNotification("Erro ao adicionar ao carrinho", "error")
      })
  }

  function showNotification(message, type = "success") {
    const Swal = window.Swal
    if (typeof Swal !== "undefined") {
      Swal.fire({
        icon: type,
        title: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      })
    } else {
      alert(message)
    }
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

/**
 * Slider automático de banners
 */
let counter = 1
const totalSlides = 4

// Marca o primeiro slide como ativo
const radio1 = document.getElementById("radio1")
if (radio1) {
  radio1.checked = true
}

/**
 * Avança para próxima imagem do slider
 */
function nextImage() {
  counter++
  if (counter > totalSlides) {
    counter = 1
  }
  const radioBtn = document.getElementById("radio" + counter)
  if (radioBtn) {
    radioBtn.checked = true
  }
}

// Troca de slide a cada 6 segundos
setInterval(nextImage, 6000)

document.addEventListener("DOMContentLoaded", () => {
  // Search functionality
  const searchInput = document.querySelector(".barra-pesquisa")
  const searchButton = document.getElementById("pesquisar")

  function performSearch() {
    const query = searchInput.value.trim()
    if (query) {
      window.location.href = `../Catalogo/catalogo.php?search=${encodeURIComponent(query)}`
    }
  }

  if (searchButton) {
    searchButton.addEventListener("click", performSearch)
  }

  if (searchInput) {
    searchInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        performSearch()
      }
    })
  }

  // Add to cart functionality
  document.querySelectorAll(".btn-add-cart").forEach((button) => {
    button.addEventListener("click", function () {
      const productId = this.getAttribute("data-id")
      addToCart(productId)
    })
  })

  // See more functionality
  document.querySelectorAll(".btn-see-more").forEach((button) => {
    button.addEventListener("click", function () {
      const productId = this.getAttribute("data-id")
      viewDetails(productId)
    })
  })

  function addToCart(productId) {
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
  }

  function viewDetails(productId) {
    window.showNotification("Página de detalhes em desenvolvimento!", "info")
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

  updateCartBadge()
})

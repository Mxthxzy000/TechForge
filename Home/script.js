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
      showProductDetails(productId)
    })
  })

  function addToCart(productId) {
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

  function showProductDetails(productId) {
    showNotification("Página de detalhes em desenvolvimento!", "info")
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

  updateCartBadge()
})

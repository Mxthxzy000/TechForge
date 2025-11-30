document.addEventListener("DOMContentLoaded", () => {
  // Função de notificação (caso não esteja carregada)
  const showNotification = (message, type) => {
    if (window.showNotification) {
      window.showNotification(message, type)
    } else {
      console.log(`Notification (${type}): ${message}`)
    }
  }

  // Função para adicionar ao carrinho
  function addToCart(idProduto) {
    fetch("../Carrinho/cartAPI.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `action=addToCart&idProduto=${idProduto}&quantidade=1`,
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
          showNotification(data.message || "Produto adicionado ao carrinho!", "success")
          updateCartBadge()
        }
      })
      .catch((error) => {
        console.error("Erro ao adicionar ao carrinho:", error)
        showNotification("Erro ao adicionar ao carrinho. Tente novamente.", "error")
      })
  }

  // Função para atualizar badge do carrinho
  function updateCartBadge() {
    fetch("../Carrinho/cartAPI.php?action=getCart")
      .then((response) => response.json())
      .then((data) => {
        if (data.needsLogin) return

        const totalItems = data.produtos.reduce((sum, item) => sum + item.quantidade, 0)

        const carrinho = document.getElementById("carrinho")
        if (carrinho) {
          const existingBadge = carrinho.querySelector(".cart-badge")
          if (existingBadge) {
            existingBadge.remove()
          }

          if (totalItems > 0) {
            const badge = document.createElement("span")
            badge.className = "cart-badge"
            badge.textContent = totalItems
            carrinho.style.position = "relative"
            carrinho.appendChild(badge)
          }
        }
      })
      .catch((error) => console.error("Erro ao atualizar badge:", error))
  }

  // Event listener do botão adicionar ao carrinho
  const btnAdicionarCarrinho = document.getElementById("btnAdicionarCarrinho")

  if (btnAdicionarCarrinho) {
    btnAdicionarCarrinho.addEventListener("click", function () {
      const idProduto = this.getAttribute("data-id")
      addToCart(idProduto)
    })
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
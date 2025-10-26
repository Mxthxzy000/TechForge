document.addEventListener("DOMContentLoaded", () => {
  const currentFilters = {
    linha: "",
    tag: "",
    precoMin: "",
    precoMax: "",
  }

  // Elementos da pesquisa
  const searchInput = document.getElementById("searchInput")
  const searchButton = document.querySelector(".btn-pesquisar")
  const suggestionsList = document.getElementById("suggestions")

  // Carregar todos os produtos inicialmente
  loadProducts()

  // Eventos da pesquisa
  searchInput.addEventListener("input", function () {
    const termo = this.value.trim()
    if (termo.length >= 2) {
      showSuggestions(termo)
    } else {
      hideSuggestions()
    }
  })

  searchInput.addEventListener("focus", function () {
    const termo = this.value.trim()
    if (termo.length >= 2) {
      showSuggestions(termo)
    }
  })

  searchButton.addEventListener("click", () => {
    performSearch(searchInput.value.trim())
  })

  searchInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      performSearch(this.value.trim())
    }
  })

  // Fechar sugestões ao clicar fora
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".divpesquisar")) {
      hideSuggestions()
    }
  })

  // Função para mostrar sugestões
  function showSuggestions(termo) {
    fetch(`funcionalidadesCatalogo.php?action=search&termo=${encodeURIComponent(termo)}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error("Erro:", data.error)
          return
        }

        if (data.produtos.length > 0) {
          suggestionsList.innerHTML = data.produtos
            .map(
              (product) => `
                        <li data-id="${product.idProduto}" data-name="${product.nomeProduto}">
                            ${product.nomeProduto}
                        </li>
                    `,
            )
            .join("")
          suggestionsList.style.display = "block"

          // Adicionar eventos às sugestões
          suggestionsList.querySelectorAll("li").forEach((item) => {
            item.addEventListener("click", function () {
              searchInput.value = this.getAttribute("data-name")
              hideSuggestions()
              performSearch(this.getAttribute("data-name"))
            })
          })
        } else {
          hideSuggestions()
        }
      })
      .catch((error) => {
        console.error("Erro na requisição:", error)
      })
  }

  // Função para esconder sugestões
  function hideSuggestions() {
    suggestionsList.style.display = "none"
  }

  // Função para executar pesquisa completa
  function performSearch(termo) {
    hideSuggestions()

    if (termo === "") {
      loadProducts()
      return
    }

    fetch(`funcionalidadesCatalogo.php?action=searchFull&termo=${encodeURIComponent(termo)}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error("Erro:", data.error)
          return
        }
        displayProducts(data.produtos)
        document.getElementById("pageTitle").textContent = `Resultados para: "${termo}"`
      })
      .catch((error) => {
        console.error("Erro na requisição:", error)
      })
  }

  // Filtros por linha (Intel/AMD)
  document.querySelectorAll(".marca-option").forEach((option) => {
    option.addEventListener("click", function () {
      const linha = this.getAttribute("data-linha")

      // Toggle - se clicar na mesma linha, desmarca
      if (currentFilters.linha === linha) {
        currentFilters.linha = ""
        this.classList.remove("active")
      } else {
        currentFilters.linha = linha
        document.querySelectorAll(".marca-option").forEach((opt) => opt.classList.remove("active"))
        this.classList.add("active")
      }

      // Limpa tags ao usar filtro de linha
      document.querySelectorAll(".tag-option").forEach((tag) => {
        tag.classList.remove("active")
      })

      applyFilters()
    })
  })

  // Filtros por tipo de peça
  document.querySelectorAll(".tipoFiltro").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const tag = this.getAttribute("data-tag")

      if (this.checked) {
        currentFilters.tag = tag
        // Desmarca outros checkboxes
        document.querySelectorAll(".tipoFiltro").forEach((cb) => {
          if (cb !== this) cb.checked = false
        })

        // Limpa tags populares
        document.querySelectorAll(".tag-option").forEach((tagEl) => {
          tagEl.classList.remove("active")
        })
      } else {
        currentFilters.tag = ""
      }

      applyFilters()
    })
  })

  // Filtro por preço
  document.getElementById("applyPrice").addEventListener("click", () => {
    currentFilters.precoMin = document.getElementById("priceMin").value
    currentFilters.precoMax = document.getElementById("priceMax").value
    applyFilters()
  })

  // EVENTOS PARA TAGS POPULARES - MÚLTIPLAS SELEÇÕES
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("tag-option")) {
      const tag = e.target.getAttribute("data-tag")

      console.log("Tag clicada:", tag)

      // Toggle da tag individual
      if (e.target.classList.contains("active")) {
        // Se já está ativa, desmarca
        e.target.classList.remove("active")
      } else {
        // Se não está ativa, marca
        e.target.classList.add("active")
      }

      // Limpa filtros de tipo ao usar tags
      document.querySelectorAll(".tipoFiltro").forEach((checkbox) => {
        checkbox.checked = false
      })
      currentFilters.tag = ""

      // Limpa filtro de linha
      document.querySelectorAll(".marca-option").forEach((option) => {
        option.classList.remove("active")
      })
      currentFilters.linha = ""

      // Coletar todas as tags ativas
      const activeTags = []
      document.querySelectorAll(".tag-option.active").forEach((tagEl) => {
        activeTags.push(tagEl.getAttribute("data-tag"))
      })

      console.log("Tags ativas:", activeTags)

      if (activeTags.length === 0) {
        // Se não há tags selecionadas, volta para todos os produtos
        loadProducts()
        document.getElementById("pageTitle").textContent = "Produtos"
      } else {
        // Filtra por múltiplas tags
        filterByMultipleTags(activeTags)
      }
    }
  })

  // Função para filtrar por múltiplas tags
  function filterByMultipleTags(tags) {
    console.log("Filtrando por múltiplas tags:", tags)

    let url = `funcionalidadesCatalogo.php?action=filterMultipleTags`

    tags.forEach((tag, index) => {
      url += `&tags[]=${encodeURIComponent(tag)}`
    })

    console.log("URL da requisição:", url)

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        console.log(`Resultados para tags "${tags.join(", ")}":`, data)

        if (data.error) {
          console.error("Erro:", data.error)
          return
        }

        displayProducts(data.produtos)

        if (data.produtos.length === 0) {
          document.getElementById("pageTitle").textContent = `Nenhum produto com as tags selecionadas`
        } else {
          document.getElementById("pageTitle").textContent = `Tags: ${tags.join(", ")}`
        }
      })
      .catch((error) => {
        console.error("Erro na requisição:", error)
      })
  }

  // Aplicar filtros
  function applyFilters() {
    manterTamanhoFiltro()

    console.log("Filtros ativos:", currentFilters)

    // Verifica se há algum filtro ativo
    const hasActiveFilter =
      currentFilters.linha || currentFilters.tag || currentFilters.precoMin || currentFilters.precoMax

    if (!hasActiveFilter) {
      // Se não há filtros ativos, carrega todos os produtos
      loadProducts()
      document.getElementById("pageTitle").textContent = "Produtos"
      return
    }

    // Monta a URL com os filtros
    let url = `funcionalidadesCatalogo.php?action=filter`

    if (currentFilters.linha) {
      url += `&linha=${encodeURIComponent(currentFilters.linha)}`
    }

    if (currentFilters.tag) {
      url += `&tag=${encodeURIComponent(currentFilters.tag)}`
    }

    if (currentFilters.precoMin) {
      url += `&precoMin=${encodeURIComponent(currentFilters.precoMin)}`
    }

    if (currentFilters.precoMax) {
      url += `&precoMax=${encodeURIComponent(currentFilters.precoMax)}`
    }

    console.log("URL da requisição:", url)

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        console.log("Resposta do servidor:", data)
        if (data.error) {
          console.error("Erro:", data.error)
          return
        }
        displayProducts(data.produtos)
        document.getElementById("pageTitle").textContent = "Produtos Filtrados"
      })
      .catch((error) => {
        console.error("Erro na requisição:", error)
      })
  }

  // Carregar todos os produtos
  function loadProducts() {
    fetch("funcionalidadesCatalogo.php?action=getAll")
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error("Erro:", data.error)
          return
        }
        displayProducts(data.produtos)
        document.getElementById("pageTitle").textContent = "Produtos"
      })
      .catch((error) => {
        console.error("Erro na requisição:", error)
      })
  }

  // Exibir produtos - FORMATAÇÃO IDÊNTICA À HOME
  function displayProducts(products) {
    const container = document.getElementById("products")
    const resultCount = document.getElementById("resultCount")

    if (products.length === 0) {
      container.innerHTML = "<p>Nenhum produto encontrado.</p>"
      return
    }

    container.innerHTML = products
      .map(
        (product) => `
            <div class="card-produto">
                <img src="../imagens_produtos/${product.imagemProduto}" alt="${product.nomeProduto}">
                <div class="card-info">
                    <h3>${product.nomeProduto}</h3>
                    <p class="preco-atual">
                        R$ ${Number.parseFloat(product.precoProduto).toFixed(2).replace(".", ",")} <span>à vista</span>
                    </p>
                    <p class="parcelamento">
                        12x de R$ ${(Number.parseFloat(product.precoProduto) / 12).toFixed(2).replace(".", ",")} sem juros
                    </p>
                </div>
                <div class="card-buttons">
                    <button class="btn-add-cart" data-id="${product.idProduto}" title="Adicionar ao carrinho">
                        <ion-icon name="cart-outline"></ion-icon>
                        Adicionar
                    </button>
                    <button class="btn-see-more" data-id="${product.idProduto}" title="Ver mais detalhes">
                        <ion-icon name="eye-outline"></ion-icon>
                        Ver Mais
                    </button>
                </div>
            </div>
        `,
      )
      .join("")

    container.querySelectorAll(".btn-add-cart").forEach((button) => {
      button.addEventListener("click", function () {
        const productId = this.getAttribute("data-id")
        addToCart(productId)
      })
    })

    container.querySelectorAll(".btn-see-more").forEach((button) => {
      button.addEventListener("click", function () {
        const productId = this.getAttribute("data-id")
        showProductDetails(productId)
      })
    })
  }

  // Função para adicionar ao carrinho
  function addToCart(productId) {
    console.log("[v0] Adding product to cart:", productId)

    // Send to database
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
            window.location.href = "../login/login.php"
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
    console.log("[v0] Showing product details:", productId)
    showNotification("Página de detalhes em desenvolvimento!", "info")
  }

  function showNotification(message, type = "success") {
    // Check if SweetAlert2 is available
    const Swal = window.Swal // Declare Swal variable here
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

  // Função para atualizar o badge do carrinho usando a API do banco de dados
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

  // Forçar tamanho do filtro
  function manterTamanhoFiltro() {
    const filtro = document.querySelector(".filtro-lateral")
    if (filtro) {
      filtro.style.width = "300px"
      filtro.style.minWidth = "300px"
      filtro.style.flexShrink = "0"
    }
  }

  // Executar quando a DOM carregar
  manterTamanhoFiltro()
})

// Elementos do DOM
const productsEl = document.getElementById("products")
const filterLinesEl = document.getElementById("filterLines")
const filterTagsEl = document.getElementById("filterTags")
const resultCount = document.getElementById("resultCount")
const searchInput = document.getElementById("searchInput")
const suggestionsList = document.getElementById("suggestions")
const priceMinEl = document.getElementById("priceMin")
const priceMaxEl = document.getElementById("priceMax")
const applyPriceBtn = document.getElementById("applyPrice")
const onlyPromos = document.getElementById("onlyPromos")

const currentFilters = {
  lines: [],
  tag: "",
  q: "",
}

// Carrega filtros e produtos iniciais
fetch("funcionalidadesCatalogo.php?action=getFilters")
  .then((r) => {
    console.log("[v0] Status da resposta:", r.status)
    if (!r.ok) {
      throw new Error(`HTTP error! status: ${r.status}`)
    }
    return r.json()
  })
  .then((data) => {
    console.log("[v0] Filtros carregados:", data)
    if (data.error) {
      throw new Error(data.error)
    }
    renderFilters(data.lines)
    renderTags(data.topTags)
    carregarProdutos()
  })
  .catch((error) => {
    console.error("[v0] Erro ao carregar filtros:", error)
    productsEl.innerHTML = `<p>Erro ao carregar filtros: ${error.message}. Recarregue a página.</p>`
  })

function renderFilters(lines) {
  filterLinesEl.innerHTML = ""
  for (const linha in lines) {
    const group = document.createElement("div")
    const title = document.createElement("h4")
    title.textContent = linha
    group.appendChild(title)

    lines[linha].forEach((t) => {
      const label = document.createElement("label")
      label.innerHTML = `<input type="checkbox" class="tipoFiltro" data-line="${linha}" value="${t.tipo}"> ${t.tipo}`
      group.appendChild(label)
    })
    filterLinesEl.appendChild(group)
  }

  document.querySelectorAll(".tipoFiltro").forEach((c) => {
    c.onchange = carregarProdutos
  })
}

function renderTags(tags) {
  filterTagsEl.innerHTML = ""
  tags.forEach((tag) => {
    const span = document.createElement("span")
    span.textContent = tag.tag
    span.className = "tag-item"
    span.onclick = () => {
      // Remove seleção anterior
      document.querySelectorAll(".tag-item").forEach((t) => t.classList.remove("selected"))
      span.classList.add("selected")
      currentFilters.tag = tag.tag
      carregarProdutos()
    }
    filterTagsEl.appendChild(span)
  })
}

const intelOption = document.getElementById("intelOption")
const amdOption = document.getElementById("amdOption")

if (intelOption && amdOption) {
  ;[intelOption, amdOption].forEach((option) => {
    option.addEventListener("click", () => {
      option.classList.toggle("selected")
      atualizarLinhasSelecionadas()
    })
  })
}

function atualizarLinhasSelecionadas() {
  const selecionadas = Array.from(document.querySelectorAll(".marca-option.selected")).map((el) => el.dataset.line)
  currentFilters.lines = selecionadas
  carregarProdutos()
}

function carregarProdutos() {
  const tipos = Array.from(document.querySelectorAll(".tipoFiltro:checked"))
    .map((c) => c.value)
    .join(",")

  const linhas = currentFilters.lines.join(",")

  const onlyPromo = onlyPromos && onlyPromos.checked ? "&categoria=promocoes" : ""

  const urlParams = new URLSearchParams(window.location.search)
  const categoriaUrl = urlParams.get("categoria")
  const categoriaParam = categoriaUrl ? `&categoria=${categoriaUrl}` : onlyPromo

  const url = `funcionalidadesCatalogo.php?action=filter&type=${tipos}&line=${linhas}&tag=${currentFilters.tag || ""}&price_min=${priceMinEl.value || 0}&price_max=${priceMaxEl.value || 0}&q=${currentFilters.q || ""}${categoriaParam}`

  console.log("[v0] Carregando produtos com URL:", url)

  fetch(url)
    .then((r) => {
      console.log("[v0] Status da resposta de produtos:", r.status)
      if (!r.ok) {
        throw new Error(`HTTP error! status: ${r.status}`)
      }
      return r.json()
    })
    .then((produtos) => {
      console.log("[v0] Produtos carregados:", produtos.length)
      if (produtos.error) {
        throw new Error(produtos.error)
      }
      renderProdutos(produtos)
    })
    .catch((error) => {
      console.error("[v0] Erro ao carregar produtos:", error)
      productsEl.innerHTML = `<p>Erro ao carregar produtos: ${error.message}. Tente novamente.</p>`
    })
}

function renderProdutos(produtos) {
  productsEl.innerHTML = ""
  resultCount.textContent = `${produtos.length} produtos encontrados`

  if (!produtos.length) {
    productsEl.innerHTML = "<p>Nenhum produto encontrado.</p>"
    return
  }

  produtos.forEach((p) => {
    const card = document.createElement("div")
    card.className = "card-produto"

    const precoAntigo = p.discount_pct > 0 ? `<p class="preco-antigo">R$ ${p.valor.toFixed(2)}</p>` : ""

    const badge = p.discount_pct > 0 ? `<span class="badge">-${p.discount_pct}% de desconto</span>` : ""

    card.innerHTML = `
      ${badge}
      <img src="${p.imagem}" alt="${p.nome}">
      <div class="card-info">
        <h3>${p.nome}</h3>
        <p class="descricao">${p.descricao}</p>
        ${precoAntigo}
        <p class="preco-atual">R$ ${p.valor_com_desconto}</p>
      </div>
    `

    card.addEventListener("click", () => {
      // window.location.href = `detalhes.php?id=${p.id}`;
      console.log("[v0] Produto clicado:", p.nome)
    })

    productsEl.appendChild(card)
  })
}

// Eventos de filtro
if (applyPriceBtn) applyPriceBtn.onclick = carregarProdutos
if (onlyPromos) onlyPromos.onchange = carregarProdutos

if (searchInput && suggestionsList) {
  searchInput.addEventListener("input", () => {
    const query = searchInput.value.trim()

    if (query.length < 2) {
      suggestionsList.style.display = "none"
      return
    }

    fetch(`funcionalidadesCatalogo.php?action=search_suggest&q=${encodeURIComponent(query)}`)
      .then((r) => r.json())
      .then((data) => {
        suggestionsList.innerHTML = ""

        if (!data.length) {
          suggestionsList.style.display = "none"
          return
        }

        data.forEach((item) => {
          const li = document.createElement("li")
          li.textContent = item.nomeProduto
          li.onclick = () => {
            searchInput.value = item.nomeProduto
            suggestionsList.style.display = "none"
            currentFilters.q = item.nomeProduto
            carregarProdutos()
          }
          suggestionsList.appendChild(li)
        })

        suggestionsList.style.display = "block"
      })
      .catch((error) => {
        console.error("[v0] Erro ao buscar sugestões:", error)
      })
  })

  // Fecha sugestões ao clicar fora
  document.addEventListener("click", (e) => {
    if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
      suggestionsList.style.display = "none"
    }
  })
}

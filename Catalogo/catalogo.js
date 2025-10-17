const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav");

hamburguer.addEventListener("click", () => {
  hamburguer.classList.toggle("open");
  nav.classList.toggle("open");
});

const User = document.querySelector(".usuario-menu");
const dropUser = document.querySelector(".dropdown-user");

if (User) {
  User.addEventListener("click", () => {
    dropUser.classList.toggle("open");
  });
}

// ==================== NOVAS FUNÇÕES DO CATÁLOGO ====================

const productsEl = document.getElementById("products");
const filterLinesEl = document.getElementById("filterLines");
const filterTagsEl = document.getElementById("filterTags");
const resultCount = document.getElementById("resultCount");
const searchInput = document.getElementById("searchInput");
const suggestionsList = document.getElementById("suggestions");
const priceMinEl = document.getElementById("priceMin");
const priceMaxEl = document.getElementById("priceMax");
const applyPriceBtn = document.getElementById("applyPrice");
const onlyPromos = document.getElementById("onlyPromos");

let currentFilters = {};

fetch("funcionalidadesCatalogo.php?action=getFilters")
  .then((r) => r.json())
  .then((data) => {
    renderFilters(data.lines);
    renderTags(data.topTags);
    carregarProdutos();
  });

function renderFilters(lines) {
  filterLinesEl.innerHTML = "";
  for (const linha in lines) {
    const group = document.createElement("div");
    const title = document.createElement("h4");
    title.textContent = linha;
    group.appendChild(title);

    lines[linha].forEach((t) => {
      const label = document.createElement("label");
      label.innerHTML = `<input type="checkbox" class="tipoFiltro" data-line="${linha}" value="${t.tipo}"> ${t.tipo}`;
      group.appendChild(label);
    });
    filterLinesEl.appendChild(group);
  }

  document.querySelectorAll(".tipoFiltro").forEach((c) => {
    c.onchange = carregarProdutos;
  });
}

function renderTags(tags) {
  filterTagsEl.innerHTML = "";
  tags.forEach((tag) => {
    const span = document.createElement("span");
    span.textContent = tag.tag;
    span.onclick = () => {
      currentFilters.tag = tag.tag;
      carregarProdutos();
    };
    filterTagsEl.appendChild(span);
  });
}

function carregarProdutos() {
  const tipos = Array.from(document.querySelectorAll(".tipoFiltro:checked"))
    .map((c) => c.value)
    .join(",");

  const linhas = (currentFilters.lines || []).join(",");

  const onlyPromo = onlyPromos && onlyPromos.checked ? "&categoria=promocoes" : "";

  fetch(
    `funcionalidadesCatalogo.php?action=filter&type=${tipos}&line=${linhas}&tag=${currentFilters.tag || ""
    }&price_min=${priceMinEl.value}&price_max=${priceMaxEl.value}${onlyPromo}`
  )
    .then((r) => r.json())
    .then((produtos) => {
      renderProdutos(produtos);
    });
}

fetch(
  `funcionalidadesCatalogo.php?action=filter&type=${tipos}&line=${linhas}&tag=${currentFilters.tag || ""
  }&price_min=${priceMinEl.value}&price_max=${priceMaxEl.value}${onlyPromo}`
)
  .then((r) => r.json())
  .then((produtos) => {
    renderProdutos(produtos);
  });


function renderProdutos(produtos) {
  productsEl.innerHTML = "";
  resultCount.textContent = `${produtos.length} produtos encontrados`;
  if (!produtos.length) {
    productsEl.innerHTML = "<p>Nenhum produto encontrado.</p>";
    return;
  }

  // ======= Seleção Intel / AMD =======
  const intelOption = document.getElementById("intelOption");
  const amdOption = document.getElementById("amdOption");

  [intelOption, amdOption].forEach((option) => {
    option.addEventListener("click", () => {
      option.classList.toggle("selected");
      atualizarLinhasSelecionadas();
    });
  });

  function atualizarLinhasSelecionadas() {
    const selecionadas = Array.from(
      document.querySelectorAll(".marca-option.selected")
    ).map((el) => el.dataset.line);
    currentFilters.lines = selecionadas;
    carregarProdutos();
  }


  produtos.forEach((p) => {
    const card = document.createElement("div");
    card.className = "card-produto";
    const precoAntigo =
      p.discount_pct > 0 ? `<p class="preco-antigo">R$ ${p.valor.toFixed(2)}</p>` : "";
    const badge =
      p.discount_pct > 0
        ? `<span class="badge">-${p.discount_pct}% de desconto</span>`
        : "";
    card.innerHTML = `
      ${badge}
      <img src="${p.imagem}" alt="${p.nome}">
      <div class="card-info">
        <h3>${p.nome}</h3>
        <p class="descricao">${p.descricao}</p>
        ${precoAntigo}
        <p class="preco-atual">R$ ${p.valor_com_desconto}</p>
      </div>
    `;
    productsEl.appendChild(card);
  });
}

applyPriceBtn.onclick = carregarProdutos;
if (onlyPromos) onlyPromos.onchange = carregarProdutos;

// Sugestões da barra de pesquisa
searchInput.addEventListener("input", () => {
  const query = searchInput.value.trim();
  if (query.length < 2) {
    suggestionsList.style.display = "none";
    return;
  }

  fetch(`funcionalidadesCatalogo.php?action=search_suggest&q=${query}`)
    .then((r) => r.json())
    .then((data) => {
      suggestionsList.innerHTML = "";
      if (!data.length) {
        suggestionsList.style.display = "none";
        return;
      }
      data.forEach((item) => {
        const li = document.createElement("li");
        li.textContent = item.nomeProduto;
        li.onclick = () => {
          searchInput.value = item.nomeProduto;
          suggestionsList.style.display = "none";
          currentFilters.q = item.nomeProduto;
          carregarProdutos();
        };
        suggestionsList.appendChild(li);
      });
      suggestionsList.style.display = "block";
    });
});

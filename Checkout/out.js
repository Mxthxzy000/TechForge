document.addEventListener("DOMContentLoaded", () => {
  const freteRates = {
    SP: { standard: 15.0, fast: 25.0, express: 40.0 },
    RJ: { standard: 18.0, fast: 28.0, express: 45.0 },
    MG: { standard: 20.0, fast: 30.0, express: 50.0 },
    default: { standard: 25.0, fast: 35.0, express: 60.0 },
  }

  let selectedEndereco = null
  let selectedPagamento = null
  let montagemId = null

  // === Helper: habilitar toggle em radio buttons ===
  function enableRadioToggle(selector, onChange) {
    document.querySelectorAll(selector).forEach((radio) => {
      radio.addEventListener("mousedown", function (e) {
        this.checked = false;
        e.stopPropagation()
      })
      
      radio.addEventListener("click", function (e) {
        if (this.wasChecked) {
          this.checked = false
          e.preventDefault()
          if (onChange) onChange(this, false)
        }
        this.wasChecked = this.checked
      })
      
      radio.addEventListener("change", function () {
        if (onChange) onChange(this, this.checked)
          
      })
    })
  }

  // === Busca de montagem via URL ===
  const urlParams = new URLSearchParams(window.location.search)
  montagemId = urlParams.get("montagem_id")

  function loadInitialTotals() {
    if (montagemId) return

    fetch("../Carrinho/cartAPI.php?action=getCart")
      .then((res) => res.json())
      .then((data) => {
        const resumoDiv = document.getElementById("resumo-produtos")
        if (!data.produtos || !data.produtos.length) {
          resumoDiv.innerHTML = '<p style="color:#999;">Nenhum produto no carrinho</p>'
          return
        }

        let resumo = '<div class="summary-section">'
        let subtotal = 0

        data.produtos.forEach((p) => {
          const preco = parseFloat(p.precoUnitario ?? p.precoProduto ?? 0)
          const qtd = parseInt(p.quantidade ?? 1)
          const total = preco * qtd
          subtotal += total
          resumo += `
            <div class="summary-item">
              <span>${p.nomeProduto ?? "Produto"}</span>
              <span>R$ ${total.toFixed(2).replace(".", ",")}</span>
            </div>`
        })
        resumo += "</div>"
        resumoDiv.innerHTML = resumo
        updateTotals()
      })
      .catch(() => {
        const div = document.getElementById("resumo-produtos")
        div.innerHTML = '<p style="color:#c33;">Erro ao carregar produtos</p>'
      })
  }

  function formatCurrency(v) {
    return "R$ " + v.toFixed(2).replace(".", ",")
  }

  function updateTotals() {
    let subtotal = 0
    if (montagemId) {
      const txt = document.getElementById("subtotal-montagem")
      if (txt) {
        const value = txt.textContent.match(/[\d,]+/)
        subtotal = parseFloat(value[0].replace(",", "."))
      }
    } else {
      document.querySelectorAll("#resumo-produtos .summary-item span:last-child").forEach((s) => {
        const v = parseFloat(s.textContent.replace("R$ ", "").replace(",", "."))
        if (!isNaN(v)) subtotal += v
      })
    }

    const freteEl = document.querySelector('input[name="frete"]:checked')
    const freteCost = freteEl ? parseFloat(freteEl.dataset.cost) : 0
    const total = subtotal + freteCost

    document.getElementById("subtotal").textContent = formatCurrency(subtotal)
    document.getElementById("frete").textContent = formatCurrency(freteCost)
    document.getElementById("total").textContent = formatCurrency(total)
  }

  // === CEP e frete ===
  const searchCepBtn = document.getElementById("searchCepBtn")
  const cepInput = document.getElementById("cepInput")

  searchCepBtn.addEventListener("click", async () => {
    const cep = cepInput.value.replace(/\D/g, "")
    if (cep.length !== 8) return showNotification("CEP inválido.", "error")

    try {
      const r = await fetch(`https://viacep.com.br/ws/${cep}/json/`)
      const data = await r.json()
      if (data.erro) return showNotification("CEP não encontrado.", "error")

      document.getElementById("street").value = data.logradouro || ""
      document.getElementById("neighborhood").value = data.bairro || ""
      document.getElementById("city").value = data.localidade || ""
      document.getElementById("state").value = data.uf || ""
      document.getElementById("resultadoEndereco").style.display = "block"
      updateShippingOptions(data.uf)
    } catch {
      showNotification("Erro ao buscar CEP.", "error")
    }
  })

  function updateShippingOptions(state) {
    const rates = freteRates[state] || freteRates.default
    const div = document.getElementById("freteOptions")
    div.innerHTML = `
      <label class="frete-option">
        <input type="radio" name="frete" value="standard" data-cost="${rates.standard}">
        <div class="frete-info"><div><strong>Frete Padrão</strong><span>5-7 dias úteis</span></div><span>${formatCurrency(rates.standard)}</span></div>
      </label>
      <label class="frete-option">
        <input type="radio" name="frete" value="fast" data-cost="${rates.fast}">
        <div class="frete-info"><div><strong>Frete Rápido</strong><span>2-4 dias úteis</span></div><span>${formatCurrency(rates.fast)}</span></div>
      </label>
      <label class="frete-option">
        <input type="radio" name="frete" value="express" data-cost="${rates.express}">
        <div class="frete-info"><div><strong>Frete Expresso</strong><span>1-2 dias úteis</span></div><span>${formatCurrency(rates.express)}</span></div>
      </label>
    `

    enableRadioToggle('input[name="frete"]', (radio, checked) => {
      document.querySelectorAll(".frete-option").forEach((el) => el.classList.remove("selected"))
      if (checked) {
        radio.closest(".frete-option").classList.add("selected")
        updateTotals()
      }
    })
  }

  // === ENDEREÇOS ===
  enableRadioToggle('.endereco-card input[type="radio"]', (radio, checked) => {
    document.querySelectorAll(".endereco-card").forEach((c) => c.classList.remove("selected"))
    if (checked) {
      const card = radio.closest(".endereco-card")
      card.classList.add("selected")
      const cepEl = card.querySelector(".cep")
      const cep = cepEl ? cepEl.textContent.replace(/\D/g, "") : ""
      if (cep) document.getElementById("cepInput").value = cep.replace(/(\d{5})(\d{3})/, "$1-$2")
      selectedEndereco = { type: "saved", idEndereco: radio.value }
      updateShippingOptions(radio.dataset.estado)
    } else {
      selectedEndereco = null
    }
  })

  // === PAGAMENTO SALVO ===
  enableRadioToggle('.pagamento-card input[type="radio"]', (radio, checked) => {
    document.querySelectorAll(".pagamento-card").forEach((c) => c.classList.remove("selected"))
    if (checked) {
      const card = radio.closest(".pagamento-card")
      card.classList.add("selected")
      document.querySelectorAll('input[name="novo_pagamento_tipo"]').forEach((r) => (r.checked = false))
      document.getElementById("cartaoForm").style.display = "none"
      document.getElementById("pixForm").style.display = "none"
      document.getElementById("boletoForm").style.display = "none"
      selectedPagamento = { type: "saved", id: radio.value }
    } else {
      selectedPagamento = null
    }
  })

  // === NOVO PAGAMENTO ===
  enableRadioToggle('input[name="novo_pagamento_tipo"]', (radio, checked) => {
    document.querySelectorAll(".pagamento-card").forEach((c) => c.classList.remove("selected"))
    document.querySelectorAll(".method-option").forEach((c) => c.classList.remove("selected"))
    document.getElementById("cartaoForm").style.display = "none"
    document.getElementById("pixForm").style.display = "none"
    document.getElementById("boletoForm").style.display = "none"

    if (checked) {
      const card = radio.closest(".method-option")
      card.classList.add("selected")
      if (radio.value === "cartao_credito") document.getElementById("cartaoForm").style.display = "block"
      else if (radio.value === "pix") document.getElementById("pixForm").style.display = "block"
      else if (radio.value === "boleto") document.getElementById("boletoForm").style.display = "block"
      selectedPagamento = { type: "new", metodo: radio.value }
    } else {
      selectedPagamento = null
    }
  })

  // === FORMATADORES ===
  document.getElementById("numeroCartao")?.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "").replace(/(\d{4})/g, "$1 ").trim()
  })
  document.getElementById("validadeCartao")?.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "").replace(/(\d{2})(\d{2})/, "$1/$2")
  })
  document.getElementById("cvvCartao")?.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "")
  })

  // === NOTIFICAÇÕES ===
  function showNotification(message, type = "info") {
    const div = document.createElement("div")
    div.className = `notification notification-${type}`
    div.textContent = message
    if (!document.querySelector("style[data-notifications]")) {
      const style = document.createElement("style")
      style.setAttribute("data-notifications", "true")
      style.textContent = `
        .notification{position:fixed;top:20px;right:20px;padding:15px 20px;border-radius:8px;font-weight:500;z-index:10000;animation:slideIn .3s ease-out}
        .notification-error{background:#fee;color:#c33;border:1px solid #fcc}
        .notification-success{background:#efe;color:#3c3;border:1px solid #cfc}
        .notification-info{background:#eef;color:#33c;border:1px solid #ccf}
        @keyframes slideIn{from{transform:translateX(400px);opacity:0}to{transform:translateX(0);opacity:1}}
      `
      document.head.appendChild(style)
    }
    document.body.appendChild(div)
    setTimeout(() => {
      div.style.animation = "slideOut .3s ease-in"
      setTimeout(() => div.remove(), 300)
    }, 4000)
  }

  loadInitialTotals()
})
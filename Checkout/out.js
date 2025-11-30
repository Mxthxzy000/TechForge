document.addEventListener("DOMContentLoaded", () => {
  let cartItems = []
  let selectedAddress = null
  let selectedPayment = null
  let selectedShipping = null
  let shippingCost = 0

  // Tabela de frete por estado (simulação - ViaCEP não fornece isso)
  const freteRates = {
    SP: { standard: 15.0, express: 30.0 },
    RJ: { standard: 20.0, express: 40.0 },
    MG: { standard: 18.0, express: 35.0 },
    default: { standard: 25.0, express: 50.0 },
  }

  // ========== FUNÇÕES AUXILIARES ==========
  function formatPrice(value) {
    return `R$ ${parseFloat(value).toFixed(2).replace(".", ",")}`
  }

  function showNotification(message, type = "info") {
    if (window.showNotification) {
      window.showNotification(message, type)
    } else {
      alert(message)
    }
  }

  // ========== CARREGAR PRODUTOS DO CARRINHO ==========
  async function loadCartItems() {
    try {
      const response = await fetch("../Carrinho/cartAPI.php?action=getCart")
      const data = await response.json()

      if (data.needsLogin) {
        showNotification("Faça login para continuar", "warning")
        window.location.href = "../Login/login.php"
        return
      }

      if (data.error) {
        showNotification(data.error, "error")
        return
      }

      cartItems = data.produtos || []
      displayCartSummary()
    } catch (error) {
      console.error("Erro ao carregar carrinho:", error)
      showNotification("Erro ao carregar produtos", "error")
    }
  }

  function displayCartSummary() {
    const container = document.getElementById("resumo-produtos")
    if (!container) return

    if (cartItems.length === 0) {
      container.innerHTML = '<p style="color:#999;text-align:center;padding:20px;">Carrinho vazio</p>'
      updateTotals()
      return
    }

    let html = '<div class="summary-section">'
    cartItems.forEach((item) => {
      const price = parseFloat(item.precoProduto || item.precoUnitario || 0)
      const qty = parseInt(item.quantidade || 1)
      const total = price * qty

      html += `
        <div class="summary-item">
          <span class="summary-item-name">${item.nomeProduto}</span>
          <span class="summary-item-value">${formatPrice(total)}</span>
        </div>
      `
    })
    html += "</div>"
    container.innerHTML = html
    updateTotals()
  }

  function updateTotals() {
    const subtotal = cartItems.reduce((sum, item) => {
      const price = parseFloat(item.precoProduto || item.precoUnitario || 0)
      const qty = parseInt(item.quantidade || 1)
      return sum + price * qty
    }, 0)

    const total = subtotal + shippingCost

    document.getElementById("subtotal").textContent = formatPrice(subtotal)
    document.getElementById("frete").textContent = formatPrice(shippingCost)
    document.getElementById("total").textContent = formatPrice(total)
  }

  // ========== CARREGAR ENDEREÇOS ==========
  async function loadAddresses() {
    try {
      const response = await fetch("../Perfil/addressAPI.php?action=getAddresses")
      const data = await response.json()

      if (data.error) {
        console.error("Erro ao carregar endereços:", data.error)
        return
      }

      displayAddresses(data.addresses || [])
    } catch (error) {
      console.error("Erro ao carregar endereços:", error)
    }
  }

  function displayAddresses(addresses) {
    const container = document.getElementById("listEnderecos")
    if (!container) return

    if (addresses.length === 0) {
      container.innerHTML = '<p class="empty-message">Nenhum endereço cadastrado. Adicione um novo abaixo.</p>'
      return
    }

    container.innerHTML = addresses
      .map(
        (addr) => `
      <label class="endereco-card">
        <input type="radio" name="endereco" value="${addr.idEndereco}" 
               class="endereco-radio" 
               data-estado="${addr.estado}"
               data-cep="${addr.cep}">
        <div class="endereco-info">
          <strong>${addr.rua}, ${addr.numero}</strong>
          ${addr.complemento ? `<p>${addr.complemento}</p>` : ""}
          <p>${addr.bairro}, ${addr.cidade} - ${addr.estado}</p>
          <p class="cep">CEP: ${addr.cep}</p>
        </div>
      </label>
    `
      )
      .join("")

    // Adicionar eventos aos radio buttons
    document.querySelectorAll(".endereco-radio").forEach((radio) => {
      radio.addEventListener("change", function () {
        if (this.checked) {
          document.querySelectorAll(".endereco-card").forEach((card) => card.classList.remove("selected"))
          this.closest(".endereco-card").classList.add("selected")

          selectedAddress = {
            idEndereco: this.value,
            estado: this.dataset.estado,
            cep: this.dataset.cep,
          }

          updateShippingOptions(this.dataset.estado)
        }
      })
    })
  }

  // ========== OPÇÕES DE FRETE ==========
  function updateShippingOptions(estado) {
    const rates = freteRates[estado] || freteRates.default
    const container = document.getElementById("freteOptions")

    container.innerHTML = `
      <label class="frete-option">
        <input type="radio" name="frete" value="standard" data-cost="${rates.standard}">
        <div class="frete-info">
          <div>
            <strong>Frete Padrão</strong>
            <span class="frete-tempo">5-7 dias úteis</span>
          </div>
          <span class="frete-cost">${formatPrice(rates.standard)}</span>
        </div>
      </label>
      <label class="frete-option">
        <input type="radio" name="frete" value="express" data-cost="${rates.express}">
        <div class="frete-info">
          <div>
            <strong>Frete Expresso</strong>
            <span class="frete-tempo">2-3 dias úteis</span>
          </div>
          <span class="frete-cost">${formatPrice(rates.express)}</span>
        </div>
      </label>
    `

    document.querySelectorAll('input[name="frete"]').forEach((radio) => {
      radio.addEventListener("change", function () {
        if (this.checked) {
          document.querySelectorAll(".frete-option").forEach((opt) => opt.classList.remove("selected"))
          this.closest(".frete-option").classList.add("selected")

          shippingCost = parseFloat(this.dataset.cost)
          selectedShipping = {
            type: this.value,
            cost: shippingCost,
          }
          updateTotals()
        }
      })
    })
  }

  // ========== BUSCAR CEP ==========
  const searchCepBtn = document.getElementById("searchCepBtn")
  const cepInput = document.getElementById("cepInput")

  if (searchCepBtn) {
    searchCepBtn.addEventListener("click", async () => {
      const cep = cepInput.value.replace(/\D/g, "")
      if (cep.length !== 8) {
        showNotification("CEP inválido. Digite 8 dígitos.", "error")
        return
      }

      searchCepBtn.disabled = true
      searchCepBtn.textContent = "Buscando..."

      try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`)
        const data = await response.json()

        if (data.erro) {
          showNotification("CEP não encontrado", "error")
          return
        }

        // Preencher campos
        document.getElementById("street").value = data.logradouro || ""
        document.getElementById("neighborhood").value = data.bairro || ""
        document.getElementById("city").value = data.localidade || ""
        document.getElementById("state").value = data.uf || ""

        document.getElementById("resultadoEndereco").style.display = "block"
        updateShippingOptions(data.uf)
      } catch (error) {
        showNotification("Erro ao buscar CEP", "error")
      } finally {
        searchCepBtn.disabled = false
        searchCepBtn.textContent = "Buscar"
      }
    })
  }

  // Formatar CEP automaticamente
  if (cepInput) {
    cepInput.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\D/g, "")
      if (value.length > 5) {
        value = value.replace(/^(\d{5})(\d)/, "$1-$2")
      }
      e.target.value = value
    })
  }

  // ========== USAR NOVO ENDEREÇO ==========
  const usarNovoBtn = document.getElementById("usarNovoEndereco")
  if (usarNovoBtn) {
    usarNovoBtn.addEventListener("click", async () => {
      const rua = document.getElementById("street").value
      const numero = document.getElementById("number").value
      const complemento = document.getElementById("complement").value
      const bairro = document.getElementById("neighborhood").value
      const cidade = document.getElementById("city").value
      const estado = document.getElementById("state").value
      const cep = cepInput.value.replace(/\D/g, "")

      if (!rua || !numero || !bairro || !cidade || !estado || !cep) {
        showNotification("Preencha todos os campos obrigatórios", "warning")
        return
      }

      usarNovoBtn.disabled = true
      usarNovoBtn.textContent = "Salvando..."

      const formData = new FormData()
      formData.append("action", "addAddress")
      formData.append("cep", cep)
      formData.append("rua", rua)
      formData.append("numero", numero)
      formData.append("complemento", complemento)
      formData.append("bairro", bairro)
      formData.append("cidade", cidade)
      formData.append("estado", estado)
      formData.append("tipoEndereco", "entrega")

      try {
        const response = await fetch("../Perfil/addressAPI.php", {
          method: "POST",
          body: formData,
        })

        const data = await response.json()

        if (data.error) {
          showNotification(data.error, "error")
        } else {
          showNotification("Endereço salvo com sucesso!", "success")
          document.getElementById("resultadoEndereco").style.display = "none"
          await loadAddresses()
        }
      } catch (error) {
        showNotification("Erro ao salvar endereço", "error")
      } finally {
        usarNovoBtn.disabled = false
        usarNovoBtn.textContent = "Usar Este Endereço"
      }
    })
  }

  // ========== CARREGAR FORMAS DE PAGAMENTO ==========
  async function loadPaymentMethods() {
    try {
      const response = await fetch("../Perfil/paymentAPI.php?action=getPaymentMethods")
      const data = await response.json()

      if (data.error) {
        console.error("Erro ao carregar pagamentos:", data.error)
        return
      }

      displayPaymentMethods(data.paymentMethods || [])
    } catch (error) {
      console.error("Erro ao carregar pagamentos:", error)
    }
  }

  function displayPaymentMethods(payments) {
    const container = document.getElementById("listPagamentos")
    if (!container) return

    if (payments.length === 0) {
      container.innerHTML = '<p class="empty-message">Nenhum método de pagamento cadastrado. Adicione um novo abaixo.</p>'
      return
    }

    container.innerHTML = payments
      .map((payment) => {
        let info = ""
        if (payment.tipoPagamento === "cartao_credito") {
          info = `
          <strong>${payment.bandeiraCartao || "Cartão"}</strong>
          <p>${payment.numeroCartao}</p>
          <p>${payment.nomeTitular}</p>
        `
        } else if (payment.tipoPagamento === "pix") {
          info = `
          <strong>PIX</strong>
          <p>${payment.chavePix}</p>
        `
        } else {
          info = `<strong>${payment.tipoPagamento}</strong>`
        }

        return `
        <label class="pagamento-card">
          <input type="radio" name="pagamento" value="${payment.idFormaPagamento}" 
                 class="pagamento-radio"
                 data-tipo="${payment.tipoPagamento}">
          <div class="pagamento-info">
            ${info}
          </div>
        </label>
      `
      })
      .join("")

    // Adicionar eventos
    document.querySelectorAll(".pagamento-radio").forEach((radio) => {
      radio.addEventListener("change", function () {
        if (this.checked) {
          document.querySelectorAll(".pagamento-card").forEach((card) => card.classList.remove("selected"))
          this.closest(".pagamento-card").classList.add("selected")

          // Desmarcar novo pagamento
          document.querySelectorAll('input[name="novo_pagamento_tipo"]').forEach((r) => (r.checked = false))
          hideAllPaymentForms()

          selectedPayment = {
            id: this.value,
            type: "saved",
            metodoPagamento: this.dataset.tipo,
          }
        }
      })
    })
  }

  function hideAllPaymentForms() {
    document.getElementById("cartaoForm").style.display = "none"
    document.getElementById("pixForm").style.display = "none"
    document.getElementById("boletoForm").style.display = "none"
  }

  // ========== NOVO PAGAMENTO ==========
  document.querySelectorAll('input[name="novo_pagamento_tipo"]').forEach((radio) => {
    radio.addEventListener("change", function () {
      if (this.checked) {
        // Desmarcar pagamentos salvos
        document.querySelectorAll(".pagamento-radio").forEach((r) => (r.checked = false))
        document.querySelectorAll(".pagamento-card").forEach((card) => card.classList.remove("selected"))
        document.querySelectorAll(".method-option").forEach((opt) => opt.classList.remove("selected"))

        this.closest(".method-option").classList.add("selected")
        hideAllPaymentForms()

        if (this.value === "cartao_credito") {
          document.getElementById("cartaoForm").style.display = "block"
        } else if (this.value === "pix") {
          document.getElementById("pixForm").style.display = "block"
        } else if (this.value === "boleto") {
          document.getElementById("boletoForm").style.display = "block"
        }

        selectedPayment = {
          type: "new",
          metodoPagamento: this.value,
        }
      }
    })
  })

  // Formatar número do cartão
  const numeroCartaoInput = document.getElementById("numeroCartao")
  if (numeroCartaoInput) {
    numeroCartaoInput.addEventListener("input", function () {
      let value = this.value.replace(/\D/g, "")
      value = value.replace(/(\d{4})(?=\d)/g, "$1 ")
      this.value = value
    })
  }

  // Formatar validade
  const validadeInput = document.getElementById("validadeCartao")
  if (validadeInput) {
    validadeInput.addEventListener("input", function () {
      let value = this.value.replace(/\D/g, "")
      if (value.length >= 2) {
        value = value.substring(0, 2) + "/" + value.substring(2, 4)
      }
      this.value = value
    })
  }

  // ========== FINALIZAR PEDIDO ==========
  const finalizarBtn = document.getElementById("finalizarBtn")
  if (finalizarBtn) {
    finalizarBtn.addEventListener("click", async () => {
      // Validações
      if (cartItems.length === 0) {
        showNotification("Seu carrinho está vazio", "warning")
        return
      }

      if (!selectedAddress) {
        showNotification("Selecione um endereço de entrega", "warning")
        return
      }

      if (!selectedShipping) {
        showNotification("Selecione uma opção de frete", "warning")
        return
      }

      if (!selectedPayment) {
        showNotification("Selecione uma forma de pagamento", "warning")
        return
      }

      // Validar novo pagamento se necessário
      if (selectedPayment.type === "new") {
        if (selectedPayment.metodoPagamento === "cartao_credito") {
          const nome = document.getElementById("nomeCartao").value
          const numero = document.getElementById("numeroCartao").value
          const validade = document.getElementById("validadeCartao").value
          const cvv = document.getElementById("cvvCartao").value

          if (!nome || !numero || !validade || !cvv) {
            showNotification("Preencha todos os dados do cartão", "warning")
            return
          }
        } else if (selectedPayment.metodoPagamento === "pix") {
          const chave = document.getElementById("chavePix").value
          if (!chave) {
            showNotification("Informe a chave PIX", "warning")
            return
          }
        }
      }

      finalizarBtn.disabled = true
      finalizarBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Processando...'

      // Preparar dados do pedido
      const orderData = {
        endereco: selectedAddress.idEndereco,
        freteType: selectedShipping.type,
        freteCost: selectedShipping.cost,
        pagamentoId: selectedPayment.type === "saved" ? selectedPayment.id : null,
        novoPagamento: selectedPayment.type === "new" ? getNovoPagamentoData() : null,
      }

      try {
        const response = await fetch("checkoutAPI.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ ...orderData, action: "finalizarPedido" }),
        })

        const data = await response.json()

        if (data.error || !data.success) {
          showNotification(data.message || data.error || "Erro ao processar pedido", "error")
          finalizarBtn.disabled = false
          finalizarBtn.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Finalizar Compra'
          return
        }

        // Sucesso!
        showNotification("Pedido realizado com sucesso!", "success")
        setTimeout(() => {
          window.location.href = "../Perfil/perfil.php"
        }, 1500)
      } catch (error) {
        console.error("Erro ao finalizar pedido:", error)
        showNotification("Erro ao processar pedido", "error")
        finalizarBtn.disabled = false
        finalizarBtn.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Finalizar Compra'
      }
    })
  }

  function getNovoPagamentoData() {
    const tipo = selectedPayment.metodoPagamento

    if (tipo === "cartao_credito") {
      return {
        tipo: "cartao_credito",
        nomeTitular: document.getElementById("nomeCartao").value,
        numeroCartao: document.getElementById("numeroCartao").value.replace(/\s/g, ""),
        validadeCartao: document.getElementById("validadeCartao").value,
        bandeiraCartao: detectCardBrand(document.getElementById("numeroCartao").value),
      }
    } else if (tipo === "pix") {
      return {
        tipo: "pix",
        chavePix: document.getElementById("chavePix").value,
      }
    } else if (tipo === "boleto") {
      return {
        tipo: "boleto",
      }
    }

    return null
  }

  function detectCardBrand(number) {
    const cleaned = number.replace(/\s/g, "")
    if (cleaned.startsWith("4")) return "Visa"
    if (cleaned.startsWith("5")) return "Mastercard"
    if (cleaned.startsWith("3")) return "American Express"
    return "Outro"
  }

  // ========== INICIALIZAÇÃO ==========
  loadCartItems()
  loadAddresses()
  loadPaymentMethods()
})
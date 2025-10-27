// Payment Method Toggle
document.addEventListener("DOMContentLoaded", () => {
  const paymentRadios = document.querySelectorAll(".payment-radio")
  const paymentDetails = document.querySelectorAll(".payment-details")

  paymentRadios.forEach((radio) => {
    radio.addEventListener("change", function () {
      // If selecting a new payment method (not saved), show the container
      if (this.name === "payment") {
        const newPaymentContainer = document.getElementById("newPaymentMethodsContainer")
        const finalizeBtn = document.getElementById("finalizeOrderBtn")

        if (newPaymentContainer) {
          newPaymentContainer.style.display = "block"
        }
        if (finalizeBtn) {
          finalizeBtn.style.display = "none"
        }

        // Uncheck saved payment methods
        document.querySelectorAll('input[name="saved-payment"]').forEach((savedRadio) => {
          savedRadio.checked = false
        })
      }

      // Remove active class from all details
      paymentDetails.forEach((detail) => {
        detail.classList.remove("active")
      })

      // Add active class to selected payment method
      if (this.checked) {
        const detailId = this.value + "-details"
        const selectedDetail = document.getElementById(detailId)
        if (selectedDetail) {
          selectedDetail.classList.add("active")
        }
      }
    })
  })

  // Credit Card Form Validation
  const creditCardForm = document.getElementById("credit-card-form")
  const cardNumberInput = document.getElementById("card-number")
  const cvvInput = document.getElementById("cvv")
  const cpfInput = document.getElementById("cpf")

  // Format card number
  cardNumberInput.addEventListener("input", (e) => {
    const value = e.target.value.replace(/\s/g, "")
    const formattedValue = value.match(/.{1,4}/g)?.join(" ") || value
    e.target.value = formattedValue
  })

  // CVV validation
  cvvInput.addEventListener("input", (e) => {
    e.target.value = e.target.value.replace(/\D/g, "")
  })

  // CPF formatting
  cpfInput.addEventListener("focus", (e) => {
    if (e.target.value === "Seu CPF aqui *") {
      e.target.value = ""
    }
  })

  cpfInput.addEventListener("blur", (e) => {
    if (e.target.value === "") {
      e.target.value = "Seu CPF aqui *"
      e.target.placeholder = ""
    }
  })

  cpfInput.addEventListener("input", (e) => {
    let value = e.target.value.replace(/\D/g, "")
    if (value.length <= 11) {
      value = value.replace(/(\d{3})(\d)/, "$1.$2")
      value = value.replace(/(\d{3})(\d)/, "$1.$2")
      value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
      e.target.value = value
    }
  })

  // Form submission
  creditCardForm.addEventListener("submit", (e) => {
    e.preventDefault()

    const cardNumber = cardNumberInput.value.replace(/\s/g, "")
    const cvv = cvvInput.value
    const month = document.getElementById("month").value
    const year = document.getElementById("year").value
    const cardName = document.getElementById("card-name").value

    if (cardNumber.length < 13 || cardNumber.length > 19) {
      alert("Número do cartão inválido")
      return
    }

    if (cvv.length < 3 || cvv.length > 4) {
      alert("CVV inválido")
      return
    }

    if (!month || !year) {
      alert("Selecione a validade do cartão")
      return
    }

    if (!cardName) {
      alert("Digite o nome do titular")
      return
    }

    console.log("[v0] Payment submitted:", {
      cardNumber: cardNumber.replace(/\d(?=\d{4})/g, "*"),
      cvv: "***",
      expiry: `${month}/${year}`,
      name: cardName,
    })

    alert("Pagamento processado com sucesso!")
  })

  // CEP Calculation
  const cepInput = document.getElementById("cep")
  const calculateBtn = document.getElementById("calculate-shipping")
  const shippingOptions = document.getElementById("shipping-options")

  // Format CEP
  cepInput.addEventListener("input", (e) => {
    let value = e.target.value.replace(/\D/g, "")
    if (value.length > 5) {
      value = value.replace(/^(\d{5})(\d)/, "$1-$2")
    }
    e.target.value = value
  })

  // Calculate shipping
  calculateBtn.addEventListener("click", () => {
    const cep = cepInput.value.replace(/\D/g, "")

    if (cep.length !== 8) {
      alert("CEP inválido. Digite um CEP válido com 8 dígitos.")
      return
    }

    console.log("[v0] Calculating shipping for CEP:", cep)

    // Simulate API call
    calculateBtn.textContent = "Calculando..."
    calculateBtn.disabled = true

    setTimeout(() => {
      const shippingData = [
        {
          name: "PAC",
          time: "Entrega em 10-15 dias úteis",
          price: "R$ 25,90",
        },
        {
          name: "SEDEX",
          time: "Entrega em 5-7 dias úteis",
          price: "R$ 45,90",
        },
        {
          name: "Expresso",
          time: "Entrega em 2-3 dias úteis",
          price: "R$ 65,90",
        },
      ]

      displayShippingOptions(shippingData)
      calculateBtn.textContent = "Calcular"
      calculateBtn.disabled = false
    }, 1500)
  })

  function displayShippingOptions(options) {
    shippingOptions.innerHTML = ""

    options.forEach((option, index) => {
      const optionDiv = document.createElement("div")
      optionDiv.className = "shipping-option"
      if (index === 0) optionDiv.classList.add("selected")

      optionDiv.innerHTML = `
                <div class="shipping-info">
                    <div class="shipping-name">${option.name}</div>
                    <div class="shipping-time">${option.time}</div>
                </div>
                <div class="shipping-price">${option.price}</div>
            `

      optionDiv.addEventListener("click", function () {
        document.querySelectorAll(".shipping-option").forEach((opt) => {
          opt.classList.remove("selected")
        })
        this.classList.add("selected")
        console.log("[v0] Selected shipping:", option.name)
      })

      shippingOptions.appendChild(optionDiv)
    })

    shippingOptions.classList.add("active")
  }

  // PIX Copy Code
  const copyCodeBtn = document.querySelector(".copy-code-btn")
  if (copyCodeBtn) {
    copyCodeBtn.addEventListener("click", function () {
      const pixCode =
        "00020126580014br.gov.bcb.pix0136a1b2c3d4-e5f6-7890-abcd-ef1234567890520400005303986540525.005802BR5913LOJA EXEMPLO6009SAO PAULO62070503***63041D3D"

      navigator.clipboard
        .writeText(pixCode)
        .then(() => {
          const originalText = this.textContent
          this.textContent = "Código copiado!"
          setTimeout(() => {
            this.textContent = originalText
          }, 2000)
        })
        .catch(() => {
          alert("Erro ao copiar código PIX")
        })
    })
  }

  // Load cart summary
  loadCheckoutSummary()

  function loadCheckoutSummary() {
    fetch("../Carrinho/cartAPI.php?action=getCart")
      .then((response) => response.json())
      .then((data) => {
        if (data.needsLogin) {
          window.location.href = "../login/login.php"
          return
        }

        if (data.error) {
          console.error("Erro:", data.error)
          return
        }

        const cartItems = data.produtos

        if (cartItems.length === 0) {
          window.location.href = "../Catalogo/catalogo.php"
          return
        }

        renderCheckoutSummary(cartItems)
      })
      .catch((error) => {
        console.error("Erro ao carregar resumo:", error)
      })
  }

  function renderCheckoutSummary(cartItems) {
    const subtotal = cartItems.reduce((sum, item) => {
      return sum + Number.parseFloat(item.precoProduto) * item.quantidade
    }, 0)

    const discount = subtotal * 0.05 // 5% discount
    const total = subtotal - discount

    // Update product info section
    const productInfoDiv = document.querySelector(".product-info")
    if (productInfoDiv && cartItems.length > 0) {
      const firstItem = cartItems[0]
      const itemCount = cartItems.reduce((sum, item) => sum + item.quantidade, 0)

      productInfoDiv.innerHTML = `
        <img src="../imagens_produtos/${firstItem.imagemProduto}" 
             alt="${firstItem.nomeProduto}" 
             class="product-image">
        <div class="product-details">
          <p class="product-name">${firstItem.nomeProduto}</p>
          ${cartItems.length > 1 ? `<p style="color: #666; font-size: 14px; margin-top: 5px;">+ ${cartItems.length - 1} outro(s) produto(s)</p>` : ""}
          <p style="color: #666; font-size: 14px; margin-top: 5px;">Total: ${itemCount} item(ns)</p>
        </div>
      `
    }

    // Update summary values
    const summaryRows = document.querySelectorAll(".summary-row")
    if (summaryRows[0]) {
      summaryRows[0].querySelector(".price").textContent = formatPrice(subtotal)
    }
    if (summaryRows[1]) {
      summaryRows[1].querySelector(".price").textContent = formatPrice(discount)
    }

    const totalPrice = document.querySelector(".total-price")
    if (totalPrice) {
      totalPrice.textContent = formatPrice(total)
    }
  }

  function formatPrice(price) {
    return `R$ ${price.toFixed(2).replace(".", ",")}`
  }

  const selectedPaymentMethod = null
  const selectedAddress = null

  // Load saved payment methods
  async function loadSavedPaymentMethods() {
    try {
      const response = await fetch("../Perfil/paymentAPI.php?action=getPaymentMethods")
      const data = await response.json()

      const container = document.getElementById("savedPaymentMethods")

      if (data.paymentMethods && data.paymentMethods.length > 0) {
        container.innerHTML = data.paymentMethods
          .map((payment) => {
            let info = ""
            if (payment.tipoPagamento === "cartao_credito") {
              info = `${payment.bandeiraCartao} •••• ${payment.numeroCartao.slice(-4)}`
            } else if (payment.tipoPagamento === "pix") {
              info = `PIX: ${payment.chavePix}`
            } else {
              info = payment.tipoPagamento.toUpperCase()
            }

            return `
            <div class="payment-option" style="cursor: pointer;" onclick="window.selectPaymentMethod(${payment.idFormaPagamento}, '${payment.tipoPagamento}')">
                <label class="payment-label">
                    <input type="radio" name="saved-payment" value="${payment.idFormaPagamento}" class="payment-radio">
                    <span class="radio-custom"></span>
                    <span class="payment-text">${info}</span>
                </label>
            </div>
          `
          })
          .join("")
      } else {
        container.innerHTML = '<p style="color: #666;">Nenhuma forma de pagamento salva. Use os métodos abaixo.</p>'
      }
    } catch (error) {
      console.error("Erro ao carregar formas de pagamento:", error)
    }
  }

  // Load saved addresses
  async function loadSavedAddresses() {
    try {
      const response = await fetch("../Perfil/addressAPI.php?action=getAddresses")
      const data = await response.json()

      const container = document.getElementById("savedAddresses")

      if (data.addresses && data.addresses.length > 0) {
        container.innerHTML = data.addresses
          .map(
            (addr) => `
            <div class="payment-option" style="cursor: pointer;" onclick="window.selectAddress(${addr.idEndereco}, '${addr.cep}')">
                <label class="payment-label">
                    <input type="radio" name="saved-address" value="${addr.idEndereco}" class="payment-radio">
                    <span class="radio-custom"></span>
                    <div class="payment-text">
                        <strong>${addr.rua}, ${addr.numero}</strong><br>
                        <small>${addr.bairro} - ${addr.cidade}/${addr.estado} - CEP: ${addr.cep}</small>
                    </div>
                </label>
            </div>
        `,
          )
          .join("")
      } else {
        container.innerHTML = '<p style="color: #666;">Nenhum endereço cadastrado.</p>'
      }
    } catch (error) {
      console.error("Erro ao carregar endereços:", error)
    }
  }

  // Handle add new address button
  document.getElementById("addNewAddress").addEventListener("click", () => {
    window.location.href = "../Perfil/perfil.php"
  })

  // Import Swal library
  const Swal = window.Swal

  document.getElementById("finalizeOrderBtn").addEventListener("click", async () => {
    if (!selectedAddress) {
      Swal.fire("Atenção!", "Por favor, selecione um endereço de entrega.", "warning")
      return
    }

    if (!selectedPaymentMethod) {
      Swal.fire("Atenção!", "Por favor, selecione uma forma de pagamento.", "warning")
      return
    }

    // Create order
    const formData = new FormData()
    formData.append("action", "createOrder")
    formData.append("idEndereco", selectedAddress)
    formData.append("metodoPagamento", selectedPaymentMethod.type)
    formData.append("idFormaPagamento", selectedPaymentMethod.id)

    try {
      const response = await fetch("checkoutAPI.php", {
        method: "POST",
        body: formData,
      })

      const result = await response.json()

      if (result.success) {
        Swal.fire({
          title: "Pedido Realizado!",
          text: "Seu pedido foi confirmado com sucesso!",
          icon: "success",
          confirmButtonText: "Ver Meus Pedidos",
        }).then(() => {
          window.location.href = "../Perfil/perfil.php"
        })
      } else {
        Swal.fire("Erro!", result.message || "Erro ao processar pedido", "error")
      }
    } catch (error) {
      console.error("Erro ao criar pedido:", error)
      Swal.fire("Erro!", "Erro ao processar pedido", "error")
    }
  })

  // Handle form submission
  document.getElementById("credit-card-form").addEventListener("submit", async (e) => {
    e.preventDefault()

    if (!selectedAddress) {
      Swal.fire("Atenção!", "Por favor, selecione um endereço de entrega.", "warning")
      return
    }

    // Create order
    const formData = new FormData()
    formData.append("action", "createOrder")
    formData.append("idEndereco", selectedAddress)
    formData.append("metodoPagamento", "cartao_credito")
    formData.append("idFormaPagamento", "")

    try {
      const response = await fetch("checkoutAPI.php", {
        method: "POST",
        body: formData,
      })

      const result = await response.json()

      if (result.success) {
        Swal.fire({
          title: "Pedido Realizado!",
          text: "Seu pedido foi confirmado com sucesso!",
          icon: "success",
          confirmButtonText: "Ver Meus Pedidos",
        }).then(() => {
          window.location.href = "../Perfil/perfil.php"
        })
      } else {
        Swal.fire("Erro!", result.message || "Erro ao processar pedido", "error")
      }
    } catch (error) {
      console.error("Erro ao criar pedido:", error)
      Swal.fire("Erro!", "Erro ao processar pedido", "error")
    }
  })

  // Load data on page load
  loadSavedPaymentMethods()
  loadSavedAddresses()
})

// Global functions for payment and address selection with toggle behavior
window.selectPaymentMethod = (id, type) => {
  const radio = document.querySelector(`input[name="saved-payment"][value="${id}"]`)
  const newPaymentContainer = document.getElementById("newPaymentMethodsContainer")
  const finalizeBtn = document.getElementById("finalizeOrderBtn")

  // Toggle behavior - if clicking the same payment method, deselect it
  if (window.selectedPaymentMethod && window.selectedPaymentMethod.id === id) {
    window.selectedPaymentMethod = null
    if (radio) radio.checked = false

    // Show new payment methods section again
    if (newPaymentContainer) newPaymentContainer.style.display = "block"
    if (finalizeBtn) finalizeBtn.style.display = "none"
  } else {
    // Select new payment method
    window.selectedPaymentMethod = { id, type }

    // Hide new payment methods section and show finalize button
    if (newPaymentContainer) newPaymentContainer.style.display = "none"
    if (finalizeBtn) finalizeBtn.style.display = "block"

    // Uncheck all new payment method radios
    document.querySelectorAll("#newPaymentMethodsContainer .payment-radio").forEach((radio) => {
      radio.checked = false
    })
  }
}

// Global function to select/deselect address
window.selectAddress = (id, cep) => {
  const radio = document.querySelector(`input[name="saved-address"][value="${id}"]`)
  const cepInput = document.getElementById("cep")
  const calculateBtn = document.getElementById("calculate-shipping")

  // Toggle behavior - if clicking the same address, deselect it
  if (window.selectedAddress === id) {
    window.selectedAddress = null
    if (radio) radio.checked = false

    // Clear CEP input
    if (cepInput) cepInput.value = ""

    // Clear shipping options
    const shippingOptions = document.getElementById("shipping-options")
    if (shippingOptions) {
      shippingOptions.innerHTML = ""
      shippingOptions.classList.remove("active")
    }
  } else {
    // Select new address
    window.selectedAddress = id

    // Auto-populate CEP in shipping calculator
    if (cepInput && cep) {
      // Format CEP with hyphen if needed
      const formattedCep = cep.replace(/^(\d{5})(\d{3})$/, "$1-$2")
      cepInput.value = formattedCep

      // Automatically trigger shipping calculation
      if (calculateBtn) {
        calculateBtn.click()
      }
    }
  }
}

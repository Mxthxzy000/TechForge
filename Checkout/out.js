// Payment Method Toggle
document.addEventListener("DOMContentLoaded", () => {
  const paymentRadios = document.querySelectorAll(".payment-radio")
  const paymentDetails = document.querySelectorAll(".payment-details")

  paymentRadios.forEach((radio) => {
    radio.addEventListener("change", function () {
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
    if (e.target.value === "9 x de R$ 24,88 (com 5% de desconto)") {
      e.target.value = ""
      e.target.placeholder = "CPF *"
    }
  })

  cpfInput.addEventListener("blur", (e) => {
    if (e.target.value === "") {
      e.target.value = "9 x de R$ 24,88 (com 5% de desconto)"
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
})

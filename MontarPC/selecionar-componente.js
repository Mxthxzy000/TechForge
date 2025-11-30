document.addEventListener("DOMContentLoaded", () => {
  // Get sessionKey from window (defined in PHP script tag)
  const sessionKey = window.sessionKey || ""

  console.log("=== DEBUG INFO ===")
  console.log("Session key recebida:", sessionKey)
  console.log("Component type:", window.componentType)

  if (!sessionKey) {
    console.error("ERRO: Session key não encontrada!")
    if (window.showNotification) {
      window.showNotification("Erro: Tipo de componente não identificado", "error")
    }
    setTimeout(() => {
      window.location.href = "montarpc.php"
    }, 2000)
    return
  }

  const productCards = document.querySelectorAll(".product-card")
  console.log(`Total de produtos encontrados: ${productCards.length}`)

  productCards.forEach((card, index) => {
    const selectBtn = card.querySelector(".btn-select-product")

    if (!selectBtn) {
      console.warn(`Botão não encontrado no card ${index}`)
      return
    }

    // Check if product is incompatible and button is disabled
    const isCompatible = card.dataset.compatible === '1'
    const buildPlatform = window.buildPlatform || ''

    if (selectBtn.disabled) {
      console.log(`Produto ${index} está desabilitado (incompatível)`)
      return
    }

    selectBtn.addEventListener("click", (e) => {
      e.preventDefault()
      e.stopPropagation()

      console.log(`=== SELEÇÃO DO PRODUTO ${index} ===`)

      const productId = card.dataset.productId
      const productName = card.dataset.productName
      const productPrice = card.dataset.productPrice
      const productImage = card.querySelector(".product-image img")?.src || ""

      console.log("Dados do produto:")
      console.log("- ID:", productId)
      console.log("- Nome:", productName)
      console.log("- Preço:", productPrice)
      console.log("- Imagem:", productImage)
      console.log("- Session Key:", sessionKey)
      console.log("- Compatível:", isCompatible)
      console.log("- Plataforma:", buildPlatform)

      // Validate data
      if (!productId || !productName || !productPrice) {
        console.error("ERRO: Dados do produto incompletos!")
        if (window.showNotification) {
          window.showNotification("Erro: Dados do produto incompletos", "error")
        }
        return
      }

      // Disable button to prevent double-click
      selectBtn.disabled = true
      selectBtn.style.opacity = "0.6"
      selectBtn.style.cursor = "not-allowed"
      selectBtn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Selecionando...'

      const requestData = {
        sessionKey: sessionKey,
        productId: productId,
        productName: productName,
        productPrice: productPrice,
        productImage: productImage,
      }

      console.log("Enviando requisição:", requestData)

      // Save to session via API
      fetch("select-component.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(requestData),
      })
        .then((response) => {
          console.log("Status da resposta:", response.status)
          console.log("Response OK:", response.ok)

          if (!response.ok) {
            throw new Error(`Erro HTTP ${response.status}: ${response.statusText}`)
          }
          return response.json()
        })
        .then((data) => {
          console.log("Resposta do servidor:", data)

          if (data.success) {
            console.log("✓ Componente selecionado com sucesso!")

            // Visual feedback
            selectBtn.innerHTML = '<ion-icon name="checkmark-done-outline"></ion-icon> Selecionado!'
            selectBtn.style.background = "#22c55e"
            selectBtn.style.color = "white"

            // Add success animation
            card.style.transform = "scale(0.98)"
            card.style.borderColor = "#22c55e"
            card.style.transition = "all 0.3s ease"

            // Show success message
            if (window.showNotification) {
              window.showNotification("Componente adicionado à montagem!", "success")
            }

            // Redirect back to builder after short delay
            setTimeout(() => {
              console.log("Redirecionando para montarpc.php...")
              window.location.href = "montarpc.php"
            }, 800)
          } else {
            throw new Error(data.error || "Erro desconhecido ao selecionar componente")
          }
        })
        .catch((error) => {
          console.error("❌ ERRO ao selecionar componente:", error)
          console.error("Stack trace:", error.stack)

          if (window.showNotification) {
            window.showNotification("Erro ao selecionar componente: " + error.message, "error")
          }

          // Reset button
          selectBtn.disabled = false
          selectBtn.style.opacity = "1"
          selectBtn.style.cursor = "pointer"
          selectBtn.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Selecionar'
        })
    })

    // Add hover effect
    card.addEventListener("mouseenter", () => {
      if (!selectBtn.disabled) {
        card.style.transform = "translateY(-4px)"
        card.style.transition = "all 0.3s ease"
      }
    })

    card.addEventListener("mouseleave", () => {
      if (!selectBtn.disabled) {
        card.style.transform = "translateY(0)"
      }
    })
  })

  console.log("=== SCRIPT INICIALIZADO COM SUCESSO ===")
})
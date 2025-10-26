document.addEventListener("DOMContentLoaded", () => {
  const productCards = document.querySelectorAll(".product-card")
  const sessionKey = "your_session_key_here" // Declare sessionKey variable here

  productCards.forEach((card) => {
    const selectBtn = card.querySelector(".btn-select-product")

    selectBtn.addEventListener("click", (e) => {
      e.stopPropagation()

      const productId = card.dataset.productId
      const productName = card.dataset.productName
      const productPrice = card.dataset.productPrice

      // Save to session via API
      fetch("select-component.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          sessionKey: sessionKey,
          productId: productId,
          productName: productName,
          productPrice: productPrice,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Visual feedback
            selectBtn.innerHTML = '<ion-icon name="checkmark-done-outline"></ion-icon> Selecionado!'
            selectBtn.style.background = "#22c55e"

            // Redirect back to builder after short delay
            setTimeout(() => {
              window.location.href = "montarpc.php"
            }, 800)
          } else {
            alert("Erro ao selecionar componente")
          }
        })
        .catch((error) => {
          console.error("Erro:", error)
          alert("Erro ao selecionar componente")
        })
    })
  })
})

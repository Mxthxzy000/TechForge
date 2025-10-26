// Importing Swal library
const Swal = window.Swal

// Funcionalidade específica da página Fale Conosco
document.getElementById("contactForm").addEventListener("submit", function (e) {
  e.preventDefault()

  const formData = new FormData(this)

  fetch("processar_contato.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "Sucesso!",
          text: "Formulário enviado com sucesso! Entraremos em contato em breve.",
          confirmButtonColor: "#1a8f1a",
        })
        this.reset()
      } else {
        Swal.fire({
          icon: "error",
          title: "Erro!",
          text: data.message || "Erro ao enviar formulário. Tente novamente.",
          confirmButtonColor: "#d9534f",
        })
      }
    })
    .catch((error) => {
      Swal.fire({
        icon: "error",
        title: "Erro!",
        text: "Erro ao enviar formulário. Tente novamente.",
        confirmButtonColor: "#d9534f",
      })
    })
})

document.querySelector(".chat-button").addEventListener("click", () => {
  Swal.fire({
    icon: "info",
    title: "Chat de Atendimento",
    text: "Iniciando chat de atendimento...",
    confirmButtonColor: "#f8c10d",
  })
})

document.querySelectorAll(".article-card").forEach((card) => {
  card.addEventListener("click", function () {
    const title = this.querySelector(".article-title").textContent.trim()
    const duvidaSelect = document.querySelector('select[name="duvida"]')

    // Map article titles to dropdown values
    let optionValue = title
    if (title === "Outros dúvidas") {
      optionValue = "Outros"
    }

    // Find and select the matching option
    const options = Array.from(duvidaSelect.options)
    const matchingOption = options.find((opt) => opt.value === optionValue)

    if (matchingOption) {
      duvidaSelect.value = matchingOption.value

      // Scroll to the form smoothly
      const formContainer = document.querySelector(".form-container")
      formContainer.scrollIntoView({ behavior: "smooth", block: "start" })

      // Add a visual highlight to the dropdown
      duvidaSelect.style.transition = "all 0.3s ease"
      duvidaSelect.style.boxShadow = "0 0 0 3px rgba(248, 193, 13, 0.3)"

      setTimeout(() => {
        duvidaSelect.style.boxShadow = ""
      }, 1500)
    }
  })
})

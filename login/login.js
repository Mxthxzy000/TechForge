const emailInput = document.getElementById("email")
const passwordInput = document.getElementById("password")

/**
 * Trata o envio do formulário de login
 * @param {Event} e
 */
const handleLoginSubmit = (e) => {
  const email = emailInput.value.trim()
  const password = passwordInput.value.trim()

  // Validação básica
  if (!email || !password) {
    e.preventDefault()
    alert("Por favor, preencha todos os campos.")
    return
  }

  // Validação de email
  const isValidEmail = (email) => {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailPattern.test(email)
  }

  if (!isValidEmail(email)) {
    e.preventDefault()
    alert("Por favor, insira um e-mail válido.")
    return
  }

  console.log("[v0] Formulário validado, enviando para logarUsuario.php")
}

const loginForm = document.getElementById("loginForm")
if (loginForm) {
  loginForm.addEventListener("submit", handleLoginSubmit)
  console.log("[v0] Event listener adicionado ao formulário de login")
}

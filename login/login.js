const emailInput = document.getElementById("email")
const passwordInput = document.getElementById("password")
const loginForm = document.getElementById("loginForm")

/**
 * Valida formato de email
 * @param {string} email
 * @returns {boolean}
 */
const isValidEmail = (email) => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailPattern.test(email)
}

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
  if (!isValidEmail(email)) {
    e.preventDefault()
    alert("Por favor, insira um e-mail válido.")
    return
  }
}

// Event listener do formulário
if (loginForm) {
  loginForm.addEventListener("submit", handleLoginSubmit)
}

// Botão de cadastro
const cadastreSeBtn = document.getElementById("cadastre-se")
if (cadastreSeBtn) {
  cadastreSeBtn.addEventListener("click", () => {
    window.location.href = "../Cadastro/cadastro.php"
  })
}

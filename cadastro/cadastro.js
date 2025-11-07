const loginForm = document.getElementById("loginForm")
const emailInput = document.getElementById("email")
const creatPasswordInput = document.getElementById("senhaUsuario1")
const passwordInput = document.getElementById("senhaUsuario")
const celularInput = document.getElementById("celular")
const nascimentoInput = document.getElementById("nascimento")

/**
 * Valida se as senhas são iguais
 * @param {string} senha1
 * @param {string} senha2
 * @returns {boolean}
 */
function validarSenhas(senha1, senha2) {
  if (senha1 !== senha2) {
    window.showNotification("As senhas não conferem!", "error")
    return false
  }
  if (senha1.length < 6) {
    window.showNotification("A senha deve ter pelo menos 6 caracteres!", "error")
    return false
  }
  return true
}

/**
 * Valida formato de email
 * @param {string} email
 * @returns {boolean}
 */
function validarEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(email)) {
    window.showNotification("E-mail inválido!", "error")
    return false
  }
  return true
}

/**
 * Valida todos os campos do formulário
 * @returns {boolean}
 */
function validarCampos() {
  const email = emailInput.value.trim()
  const senha = passwordInput.value.trim()
  const senhaCriada = creatPasswordInput.value.trim()
  const celular = celularInput.value.trim()
  const nascimento = nascimentoInput.value.trim()

  if (!email || !senha || !senhaCriada || !celular || !nascimento) {
    window.showNotification("Preencha todos os campos!", "warning")
    return false
  }

  if (!validarEmail(email)) return false
  if (!validarSenhas(senhaCriada, senha)) return false

  return true
}

// Intercepta submit do formulário
if (loginForm) {
  loginForm.addEventListener("submit", (e) => {
    if (!validarCampos()) {
      e.preventDefault()
    }
  })
}

// Botão de fazer login
const fazerLoginBtn = document.getElementById("fazerlogin")
if (fazerLoginBtn) {
  fazerLoginBtn.addEventListener("click", () => {
    window.location.href = "../Login/login.php"
  })
}

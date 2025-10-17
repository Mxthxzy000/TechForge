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
    alert("As senhas não conferem!")
    return false
  }
  if (senha1.length < 6) {
    alert("A senha deve ter pelo menos 6 caracteres!")
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
    alert("E-mail inválido!")
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
    alert("Preencha todos os campos!")
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

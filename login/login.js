// Seletores principais
const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav");
const loginForm = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const forgotPasswordButton = document.querySelector(".forgot-password-button");
const inputs = document.querySelectorAll(".input-field");

// -----------------------------
// Funções
// -----------------------------

/**
 * Alterna o menu hamburguer
 */
const toggleHamburguerMenu = () => {
  hamburguer.classList.toggle("open");
  nav.classList.toggle("open");
};

/**
 * Valida email
 * @param {string} email 
 * @returns {boolean}
 */
const isValidEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

/**
 * Trata o envio do formulário de login
 * @param {Event} e 
 */
const handleLoginSubmit = (e) => {
  e.preventDefault();

  const email = emailInput.value.trim();
  const password = passwordInput.value.trim();

  // Validação básica
  if (!email || !password) {
    alert("Por favor, preencha todos os campos.");
    return;
  }

  // Validação de email
  if (!isValidEmail(email)) {
    alert("Por favor, insira um e-mail válido.");
    return;
  }

  // Simulação de login
  console.log("Tentativa de login:", { email, password });
  alert("Login realizado com sucesso!");

  // Aqui você enviaria os dados para o servidor, por exemplo:
  // fetch('/api/login', { method: 'POST', body: JSON.stringify({ email, password }) })
};

/**
 * Redireciona para a página de cadastro ao clicar em "Esqueceu a senha"
 */
const handleForgotPassword = () => {
  window.location.href = "cadastro.html";
};

/**
 * Adiciona efeitos de foco nos inputs
 */
const addInputFocusEffects = () => {
  inputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.classList.add("focused");
    });

    input.addEventListener("blur", function () {
      this.parentElement.classList.remove("focused");
    });
  });
};

// -----------------------------
// Event Listeners
// -----------------------------

// Menu hamburguer
hamburguer.addEventListener("click", toggleHamburguerMenu);

// Formulário de login
if (loginForm) {
  loginForm.addEventListener("submit", handleLoginSubmit);
}

// Botão "Esqueceu a senha"
if (forgotPasswordButton) {
  forgotPasswordButton.addEventListener("click", handleForgotPassword);
}

// Efeitos de foco nos inputs
addInputFocusEffects();

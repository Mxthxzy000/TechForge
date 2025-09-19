const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav")

hamburguer.addEventListener("click", () => {
    hamburguer.classList.toggle("open");
    nav.classList.toggle("open")
});

document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm")
  const emailInput = document.getElementById("email")
  const passwordInput = document.getElementById("password")
  const forgotPasswordButton = document.querySelector(".forgot-password-button")

  // Handle form submission
  loginForm.addEventListener("submit", (e) => {
    e.preventDefault()

    const email = emailInput.value.trim()
    const password = passwordInput.value.trim()

    // Basic validation
    if (!email || !password) {
      alert("Por favor, preencha todos os campos.")
      return
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(email)) {
      alert("Por favor, insira um e-mail válido.")
      return
    }

    // Simulate login process
    console.log("Tentativa de login:", { email, password })
    alert("Login realizado com sucesso!")

    // Here you would typically send the data to your server
    // Example: fetch('/api/login', { method: 'POST', body: JSON.stringify({ email, password }) })
  })

  // Handle forgot password button
 forgotPasswordButton.addEventListener("click", () => {
  window.location.href = "cadastro.html"; 
});

  // Add input focus effects
  const inputs = document.querySelectorAll(".input-field")
  inputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.classList.add("focused")
    })

    input.addEventListener("blur", function () {
      this.parentElement.classList.remove("focused")
    })
  })
})
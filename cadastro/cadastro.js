// ================================
// Seletores Globais
// ================================
const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav");
const loginForm = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const creatPasswordInput = document.getElementById("creatpassword");
const passwordInput = document.getElementById("password");
const celularInput = document.getElementById("celular");
const nascimentoInput = document.getElementById("nascimento");
const forgotPasswordButton = document.querySelector(".forgot-password-button");

// ================================
// Funções de UI
// ================================

// Alterna menu hamburguer
function toggleMenu() {
    hamburguer.classList.toggle("open");
    nav.classList.toggle("open");
}

// Adiciona foco estilizado nos inputs
function handleInputFocus(input) {
    input.addEventListener("focus", function () {
        this.parentElement.classList.add("focused");
    });

    input.addEventListener("blur", function () {
        this.parentElement.classList.remove("focused");
    });
}

// ================================
// Validações
// ================================

// Validação e formatação de celular
function formatCelular(e) {
    let value = e.target.value.replace(/\D/g, ""); // remove tudo que não é número
    if (value.length > 11) value = value.slice(0, 11); // limita a 11 dígitos

    if (value.length > 6) {
        e.target.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
    } else if (value.length > 2) {
        e.target.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
    } else {
        e.target.value = value;
    }
}

// Regex para celular válido
function validarCelular(celular) {
    const celularRegex = /^(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})\-?(\d{4}))$/;
    return celularRegex.test(celular);
}

// Validação de senha (iguais)
function validarSenhas(senha1, senha2) {
    if (senha1 !== senha2) {
        alert("As senhas não conferem!");
        return false;
    }
    return true;
}

// Regex e formatação de data de nascimento
function formatNascimento(e) {
    let valor = e.target.value.replace(/\D/g, ""); // só números
    if (valor.length > 2 && valor.length <= 4) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2);
    } else if (valor.length > 4) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2, 4) + "/" + valor.slice(4, 8);
    }
    e.target.value = valor;
}

// Validação de data de nascimento
function validarNascimento(valor) {
    const regexData = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/[0-9]{4}$/;
    if (!regexData.test(valor)) {
        alert("Data inválida! Use o formato dd/mm/aaaa.");
        return false;
    }

    // Verificação de idade <= 100 anos
    const hoje = new Date();
    const partes = valor.split("/");
    const dataNascimento = new Date(`${partes[2]}-${partes[1]}-${partes[0]}`);
    const idade = hoje.getFullYear() - dataNascimento.getFullYear();
    const mes = hoje.getMonth() - dataNascimento.getMonth();
    const dia = hoje.getDate() - dataNascimento.getDate();

    const idadeReal = mes < 0 || (mes === 0 && dia < 0) ? idade - 1 : idade;

    if (idadeReal < 0 || idadeReal > 100) {
        alert("A idade deve ser entre 0 e 100 anos.");
        return false;
    }
    return true;
}

// Regex de e-mail
function validarEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// ================================
// Formulário de Login
// ================================
function handleFormSubmit(e) {
    e.preventDefault();

    const email = emailInput.value.trim();
    const senha = passwordInput.value.trim();
    const senhaCriada = creatPasswordInput.value.trim();
    const celular = celularInput.value.trim();
    const nascimento = nascimentoInput.value.trim();

    // Validações básicas
    if (!email || !senha || !celular || !nascimento) {
        alert("Por favor, preencha todos os campos.");
        return;
    }

    if (!validarEmail(email)) {
        alert("Por favor, insira um e-mail válido.");
        return;
    }

    if (!validarCelular(celular)) {
        alert("Por favor, insira um número de celular válido.");
        return;
    }

    if (!validarSenhas(senhaCriada, senha)) return;

    if (!validarNascimento(nascimento)) return;

    // Simula login/cadastro
    console.log("Tentativa de login/cadastro:", { email, senha, celular, nascimento });
    alert("Cadastro/Login realizado com sucesso!");
}

// ================================
// Eventos
// ================================
document.addEventListener("DOMContentLoaded", () => {
    // Hamburguer menu
    hamburguer.addEventListener("click", toggleMenu);

    // Validação de celular
    celularInput.addEventListener("input", formatCelular);

    // Validação de nascimento
    nascimentoInput.addEventListener("input", formatNascimento);
    nascimentoInput.addEventListener("blur", () => validarNascimento(nascimentoInput.value));

    // Submissão do formulário
    loginForm.addEventListener("submit", handleFormSubmit);

    // Botão esqueci minha senha
    forgotPasswordButton.addEventListener("click", () => {
        window.location.href = "../Login/login.html";
    });

    // Estilização ao focar nos inputs
    const inputs = document.querySelectorAll(".input-field");
    inputs.forEach((input) => handleInputFocus(input));
});

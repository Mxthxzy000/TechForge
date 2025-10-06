const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav");
const loginForm = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const creatPasswordInput = document.getElementById("senhaUsuario1");
const passwordInput = document.getElementById("senhaUsuario");
const celularInput = document.getElementById("celular");
const nascimentoInput = document.getElementById("nascimento");
const forgotPasswordButton = document.querySelector(".forgot-password-button");


// Funções de UI
function toggleMenu() {
    hamburguer.classList.toggle("open");
    nav.classList.toggle("open");
}

function handleInputFocus(input) {
    input.addEventListener("focus", function () {
        this.parentElement.classList.add("focused");
    });

    input.addEventListener("blur", function () {
        this.parentElement.classList.remove("focused");
    });
}


// Validações
function formatCelular(e) {
    let value = e.target.value.replace(/\D/g, "");
    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 6) {
        e.target.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
    } else if (value.length > 2) {
        e.target.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
    } else {
        e.target.value = value;
    }
}

function validarCelular(celular) {
    const celularRegex = /^(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})\-?(\d{4}))$/;
    return celularRegex.test(celular);
}

function validarSenhas(senha1, senha2) {
    if (senha1 !== senha2) {
        Swal.fire({
            title: "Senhas não conferem!",
            text: "As senhas digitadas são diferentes.",
            icon: "warning"
        });
        return false;
    }
    return true;
}

function formatNascimento(e) {
    let valor = e.target.value.replace(/\D/g, "");
    if (valor.length > 2 && valor.length <= 4) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2);
    } else if (valor.length > 4) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2, 4) + "/" + valor.slice(4, 8);
    }
    e.target.value = valor;
}

function validarNascimento(valor) {
    const regexData = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/[0-9]{4}$/;
    if (!regexData.test(valor)) {
        Swal.fire({
            title: "Data inválida!",
            text: "Use o formato dd/mm/aaaa.",
            icon: "error"
        });
        return false;
    }

    const hoje = new Date();
    const partes = valor.split("/");
    const dataNascimento = new Date(`${partes[2]}-${partes[1]}-${partes[0]}`);
    const idade = hoje.getFullYear() - dataNascimento.getFullYear();
    const mes = hoje.getMonth() - dataNascimento.getMonth();
    const dia = hoje.getDate() - dataNascimento.getDate();
    const idadeReal = mes < 0 || (mes === 0 && dia < 0) ? idade - 1 : idade;

    if (idadeReal < 0 || idadeReal > 100) {
        Swal.fire({
            title: "Idade inválida!",
            text: "A idade deve ser entre 0 e 100 anos.",
            icon: "warning"
        });
        return false;
    }
    return true;
}

function validarEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Submissão do Formulário

function handleFormSubmit(e) {
    e.preventDefault();

    const email = emailInput.value.trim();
    const senha = passwordInput.value.trim();
    const senhaCriada = creatPasswordInput.value.trim();
    const celular = celularInput.value.trim();
    const nascimento = nascimentoInput.value.trim();

    if (!email || !senha || !celular || !nascimento || !senhaCriada) {
        Swal.fire({
            title: "Campos obrigatórios!",
            text: "Por favor, preencha todos os campos.",
            icon: "warning"
        });
        return;
    }

    if (!validarEmail(email)) {
        Swal.fire({
            title: "E-mail inválido!",
            text: "Por favor, insira um e-mail válido.",
            icon: "error"
        });
        return;
    }

    if (!validarCelular(celular)) {
        Swal.fire({
            title: "Celular inválido!",
            text: "Insira um número de celular válido.",
            icon: "error"
        });
        return;
    }

    if (!validarSenhas(senhaCriada, senha)) return;

    if (!validarNascimento(nascimento)) return;

    Swal.fire({
        title: "Cadastro realizado!",
        text: "Seus dados foram validados com sucesso.",
        icon: "success",
        confirmButtonText: "Ok"
    }).then(() => {
        loginForm.submit();
    });
}

// Eventos
document.addEventListener("DOMContentLoaded", () => {
    hamburguer.addEventListener("click", toggleMenu);
    celularInput.addEventListener("input", formatCelular);
    nascimentoInput.addEventListener("input", formatNascimento);
    nascimentoInput.addEventListener("blur", () => validarNascimento(nascimentoInput.value));

    loginForm.addEventListener("submit", handleFormSubmit);

    forgotPasswordButton.addEventListener("click", () => {
        window.location.href = "../Login/login.php";
    });

    const inputs = document.querySelectorAll(".input-field");
    inputs.forEach((input) => handleInputFocus(input));
});

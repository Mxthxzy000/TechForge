const loginForm = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const creatPasswordInput = document.getElementById("senhaUsuario1");
const passwordInput = document.getElementById("senhaUsuario");
const celularInput = document.getElementById("celular");
const nascimentoInput = document.getElementById("nascimento");

// Validações
function validarSenhas(senha1, senha2) {
    if (senha1 !== senha2) {
        Swal.fire('Erro!', 'As senhas não conferem!', 'warning');
        return false;
    }
    return true;
}

function validarEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(email)) {
        Swal.fire('Erro!', 'E-mail inválido!', 'warning');
        return false;
    }
    return true;
}

function validarCampos() {
    const email = emailInput.value.trim();
    const senha = passwordInput.value.trim();
    const senhaCriada = creatPasswordInput.value.trim();
    const celular = celularInput.value.trim();
    const nascimento = nascimentoInput.value.trim();

    if(!email || !senha || !senhaCriada || !celular || !nascimento) {
        Swal.fire('Erro!', 'Preencha todos os campos!', 'warning');
        return false;
    }

    if(!validarEmail(email)) return false;
    if(!validarSenhas(senhaCriada, senha)) return false;

    return true;
}

// Intercepta submit
loginForm.addEventListener("submit", (e) => {
    if(!validarCampos()) e.preventDefault(); // cancela envio se inválido
});

document.getElementById('fazerlogin').addEventListener('click', function() {
    window.location.href = '../Login/login.php';
});

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Formulário enviado com sucesso! Entraremos em contato em breve.');
            this.reset();
        });

        document.querySelector('.chat-button').addEventListener('click', function() {
            alert('Iniciando chat de atendimento...');
        });

        document.querySelectorAll('.article-card').forEach(card => {
            card.addEventListener('click', function() {
                const title = this.querySelector('.article-title').textContent;
                alert('Abrindo artigo: ' + title);
            });
        });

        const hamburguer = document.querySelector(".hamburguer-menu");
const nav = document.querySelector("nav")


hamburguer.addEventListener("click", () => {
    hamburguer.classList.toggle("open");
    nav.classList.toggle("open")
});

const User = document.querySelector(".usuario-menu");
const dropUser = document.querySelector(".dropdown-user")


User.addEventListener("click", () => {
    dropUser.classList.toggle("open")
});

document.getElementById('cadastre-se').addEventListener('click', function() {
    window.location.href = '../Cadastro/cadastro.php';
});
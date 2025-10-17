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
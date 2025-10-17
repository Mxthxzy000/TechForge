<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fale.css">
    <title>Fale Conosco</title>
</head>

<body>
    <header>
        <div class="inicio-header">
            <div class="hamburguer-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <img src="../imagens/logo_header_TechForge.png" alt="TechForge Logo" class="logo">
        </div>
        <div class="final-header">
            <button id="minha-conta" class="btn-header"><ion-icon name="person-circle-outline"></ion-icon></button>
            <button id="carrinho" class="btn-header"><ion-icon name="cart-outline"></ion-icon></button>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="../Home/index.php" class="voltarInicio"><ion-icon name="arrow-back-circle-outline"></ion-icon>INÍCIO</a></li>
        </ul>
    </nav>    
    
     <div class="container-new">
        <div class="left-section">
            <div class="articles-section">
                <h2 class="section-title">Artigos mais populares</h2>
                <div class="articles-list">
                    <div class="article-card">
                        <div class="article-title">Abertura de ticket</div>
                        <div class="article-description">Esclarecimento de dúvidas do cliente</div>
                    </div>
                    <div class="article-card">
                        <div class="article-title">Consulta de pedido</div>
                        <div class="article-description">Consulta de envio</div>
                    </div>
                    <div class="article-card">
                        <div class="article-title">Rastreio</div>
                        <div class="article-description">Pedido em processo de entrega</div>
                    </div>
                    <div class="article-card">
                        <div class="article-title">Outros dúvidas</div>
                        <div class="article-description">Auxílio em questionamentos diversos.</div>
                    </div>
                </div>
            </div>

            <div class="contact-options">
                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <div class="contact-label">Telefone SAC</div>
                    <div class="status offline">
                        <span class="status-dot"></span>
                        OFFLINE
                    </div>
                    <div class="phone-number">(12) 96637-1678</div>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">💬</div>
                    <div class="contact-label">Chat instantâneo de atendimento</div>
                    <div class="status online">
                        <span class="status-dot"></span>
                        ONLINE
                    </div>
                    <button class="chat-button">INICIAR ATENDIMENTO</button>
                </div>
            </div>
        </div>

        <div class="form-container">
            <h2 class="form-title">Fale Conosco</h2>
            <form id="contactForm">
                <div class="form-row">
                    <input type="text" class="form-input" placeholder="Nome" required>
                    <input type="tel" class="form-input" placeholder="Celular" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-input" placeholder="E-mail" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-input" placeholder="Dúvida" required>
                </div>
                <div class="form-group">
                    <textarea class="form-textarea" placeholder="Sobre..." required></textarea>
                </div>
                <button type="submit" class="submit-button">Enviar</button>
                <div class="form-footer">
                    Nesse atendimento a resposta dentro de um prazo de até <a href="#">3 dias úteis</a>.<br>
                    <a href="#">Horário de expediente</a>: Segunda à Sexta-feira, das 08:15 às 12:00 e das 13:00 às 18:00.
                </div>
            </form>
        </div>
    </div>


    <footer>
            <div class="container-footer">
                <ul>
                    <h3>TECHFORGE</h3>
                    <div class="links">
                        <li><a href="#">Sobre nós</a></li>
                        <li><a href="#">Política De Privacidade</a></li>
                        <li><a href="#">Parceiros</a></li>
                    </div>
                </ul>

                <ul>
                    <h3>AJUDA</h3>
                    <div class="links">
                        <li><a href="../Fale Conosco/fale.php">Fale Conosco</a></li>
                        <li><a href="#">Chat Suporte</a></li>
                        <li><a href="../Perfil/perfil.php">Sua Conta</a></li>
                    </div>
                </ul>

                <ul>
                    <h3>SERVIÇOS</h3>
                    <div class="links">
                        <li><a href="../Catalogo/catalogo.php">Catálogo</a></li>
                        <li><a href="../Fale Conosco/fale.php">Suporte</a></li>
                        <li><a href="#">Como Escolher</a></li>
                    </div>
                </ul>

                <ul>
                    <h3>SIGA-NOS</h3>
                    <div class="links-icon">
                        <ion-icon name="logo-instagram"></ion-icon>
                        <ion-icon name="logo-twitter"></ion-icon>
                        <ion-icon name="logo-youtube"></ion-icon>
                        <ion-icon name="logo-linkedin"></ion-icon>
                    </div>
                    <div class="title">
                        <p>Em Nossas Redes Sociais</p>
                    </div>
                </ul>
            </div>

            <p id="finalfooter"> ©2025 TechForge. Todos os Direitos Reservados | Caçapava SP </p>

        </footer>

    <script src="fale.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
<?php
require '../config.php';
require '../session.php';
require '../flash.php';

if (!isset($conn)) {
    die("Erro: Conexão com banco de dados não estabelecida.");
}

// Verifica se o usuário está logado
if (empty($_SESSION['idUsuario'])) {
    // Redireciona para a página de login
    header("Location: ../Login/login.php");
    exit(); // sempre colocar exit após header para interromper o script
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="fale.css">
    <title>Fale Conosco | TechForge</title>
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
            <div class="usuario-menu">
                <button id="minha-conta" class="btn-header">
                    <ion-icon name="person-circle-outline"></ion-icon>
                </button>
            </div>
            <button id="carrinho" class="btn-header"><ion-icon name="cart-outline"></ion-icon></button>
        </div>
    </header>

    <div class="dropdown-user">
        <?php if (!empty($_SESSION['idUsuario'])): ?>
            <a href="../Perfil/perfil.php" class="menu-usuario"
                style="justify-content: space-between; align-items: center;">
                <span>Olá, <?php echo htmlspecialchars($_SESSION['nomeUsuario']); ?>...</span>

                <?php if (!empty($_SESSION['fotoUsuario'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['fotoUsuario']); ?>" alt="Foto do Usuário"
                        class="foto-usuario" style="width:26px;height:26px;border-radius:50%;object-fit:cover;">
                <?php else: ?>
                    <ion-icon name="person-circle-outline" class="icon-user"></ion-icon>
                <?php endif; ?>
            </a>

            <form method="POST" action="../logout.php">
                <button type="submit" class="menu-usuario">
                    Sair!
                    <ion-icon name="log-out-outline" class="icon-user"></ion-icon>
                </button>
            </form>

        <?php else: ?>
            <a href="../Login/login.php" class="menu-usuario">
                Fazer Login!
                <ion-icon name="log-in-outline" class="icon-user"></ion-icon>
            </a>
        <?php endif; ?>
    </div>

    <nav>
        <ul>
            <li><a href="../Home/index.php">HOME</a> <ion-icon class="navicon" name="home-outline"></ion-icon> </li>
            <span class="linha"></span>
            <li><a href="../Catalogo/catalogo.php">PRODUTOS</a> <ion-icon name="bag-outline" class="navicon"></ion-icon>
            </li>
            <span class="linha"></span>
            <li><a href="../MontarPC/montarpc.php">MONTE SEU PC</a> <ion-icon class="navicon" name="desktop-outline"></ion-icon> </li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a> <ion-icon class="navicon" name="business-outline"></ion-icon>
            </li>
        </ul>
    </nav>

    <?php show_flash(); ?>

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
            <!-- Adicionando action para processar formulário -->
            <form id="contactForm" method="POST" action="processar_contato.php">
                <div class="form-row">
                    <input type="text" name="nome" class="form-input" placeholder="Nome" required>
                    <input type="tel" name="celular" class="form-input" placeholder="Celular" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder="E-mail" required>
                </div>
                <!-- Changed text input to dropdown select for duvida -->
                <div class="form-group">
                    <select name="duvida" class="form-input form-select" required>
                        <option value="" disabled selected>Selecione o tipo de dúvida</option>
                        <option value="Abertura de ticket">Abertura de ticket</option>
                        <option value="Consulta de pedido">Consulta de pedido</option>
                        <option value="Rastreio">Rastreio</option>
                        <option value="Problemas com produto">Problemas com produto</option>
                        <option value="Devolução/Troca">Devolução/Troca</option>
                        <option value="Pagamento">Pagamento</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="mensagem" class="form-textarea" placeholder="Descreva sua dúvida..." required></textarea>
                </div>
                <button type="submit" class="submit-button">Enviar</button>
                <div class="form-footer">
                    Nesse atendimento a resposta dentro de um prazo de até <a href="#">6 dias úteis</a>.<br>
                    <a href="#">Horário de expediente</a>: Segunda e Sexta-feira, das 08:15 às 12:00 e das 13:30 às
                    16:00.
                </div>
            </form>
        </div>
    </div>

    <button class="chat-toggle-button" id="chatToggleButton">
        <ion-icon name="chatbox-outline"></ion-icon>
    </button>

    <div class="chat-container" id="chatContainer" style="display: none;">
        <div class="chat-header">
            <button class="close-button" id="closeButton">
               <ion-icon name="close-outline"></ion-icon>
            </button>
            <h1>ChatBot</h1>
            <p>Techforge Assistant</p>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="message assistant">
                <div class="message-content">
                    Bem-vindo ao suporte da TechForge! 🔧💻 Aqui você encontra as melhores soluções em hardware e acessórios de alta performance. Como posso te ajudar hoje? - ❓ Dúvidas sobre produtos - 🚚 Status ou rastreio do seu pedido - 🛠️ Garantia e suporte técnico - 🛒 Ajuda para realizar uma compra Estou aqui para garantir que você tenha a melhor experiência com a TechForge. Me diga, em que podemos ajudar?
                    <div class="message-time">
                        <?php echo date('H:i'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>

        <div class="chat-input-container">
            <form class="chat-input-form" id="chatForm">
                <input type="text" class="chat-input" id="messageInput" placeholder="Digite sua mensagem..."
                    autocomplete="off" required>
                <button type="submit" class="send-button" id="sendButton">
                    Enviar
                </button>
            </form>
        </div>
    </div>
    <script src="../chatbot/script.js"></script>

    <footer>
        <div class="container-footer">
            <ul>
                <h3>TECHFORGE</h3>
                <div class="links">
                    <li><a href="../Sobre/sobre.php">Sobre nós</a></li>
                    <li><a href="#">Política De Privacidade</a></li>
                </div>
            </ul>

            <ul>
                <h3>AJUDA</h3>
                <div class="links">
                    <li><a href="../Fale Conosco/fale.php">Fale Conosco</a></li>
                    <li><a href="../Perfil/perfil.php">Sua Conta</a></li>
                </div>
            </ul>

            <ul>
                <h3>SERVIÇOS</h3>
                <div class="links">
                    <li><a href="../Catalogo/catalogo.php">Catálogo</a></li>
                    <li><a href="../Fale Conosco/fale.php">Suporte</a></li>
                </div>
            </ul>

            <ul>
                <h3>SIGA-NOS</h3>
                <div class="links-icon">
                    <ion-icon name="logo-instagram"></ion-icon>
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

    <!-- Adicionando common.js antes do script específico -->
    <script src="../Comum/common.js"></script>
    <script src="fale.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>

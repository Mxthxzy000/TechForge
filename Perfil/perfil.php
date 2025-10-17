<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="perfil.css">
    <title>Perfil</title>
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
            <li><a href="../Home/index.php" ><ion-icon name="arrow-back-circle-outline"></ion-icon>INÍCIO </a></li>
        </ul>
    </nav>      

        <div class="container-new">
        <!-- Header -->
        <div class="header-new">
            <div class="user-info">
                <div class="user-avatar">👤</div>
                <div class="user-details">
                    <h2>Olá, Greggori</h2>
                    <div class="user-email">greggori.lol@gmail.com</div>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary">Rastrear</button>
                <button class="btn btn-secondary">Editar</button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" data-tab="pedidos">Meus Pedidos</button>
            <button class="tab" data-tab="dados">Meus Dados</button>
            <button class="tab" data-tab="endereco">Endereço</button>
            <button class="tab" data-tab="favoritos">Favoritos</button>
        </div>

        <!-- Orders Section -->
        <div class="orders-section">
            <div class="section-title">Últimos Pedidos</div>

            <!-- Order Card 1 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-field">
                        <div class="order-label">Número do Pedido</div>
                        <div class="order-value">#12345</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Pagamento</div>
                        <div class="order-value">PIX</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Data</div>
                        <div class="order-value">15/01/2025</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Valor Total</div>
                        <div class="order-value">R$ 234,90</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Status</div>
                        <div class="order-value">CONCLUÍDO</div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-badges">
                        <span class="badge badge-success">EM ENTREGA</span>
                        <span class="badge badge-warning">Aguardando Entrega</span>
                        <span class="badge badge-warning">Aguardando Entrega</span>
                    </div>
                    <div class="order-location">
                        <span class="location-icon">📍</span>
                        <span>Produto Saindo</span>
                        <span>Recife</span>
                    </div>
                </div>
            </div>

            <!-- Order Card 2 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-field">
                        <div class="order-label">Número do Pedido</div>
                        <div class="order-value">CART-01-00-00-0TC</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Pagamento</div>
                        <div class="order-value">PIX</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Data</div>
                        <div class="order-value">10/01/2025</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Valor Total</div>
                        <div class="order-value">R$ 156,50</div>
                    </div>
                    <div class="order-field">
                        <div class="order-label">Status</div>
                        <div class="order-value">CONCLUÍDO</div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-badges">
                        <span class="badge badge-success">EM ENTREGA</span>
                        <span class="badge badge-warning">Aguardando Entrega</span>
                        <span class="badge badge-warning">Aguardando Entrega</span>
                    </div>
                    <div class="order-location">
                        <span class="location-icon">📍</span>
                        <span>Produto Saindo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="bottom-grid">
            <!-- Addresses Section -->
            <div class="addresses-section">
                <div class="addresses-header">
                    <div class="addresses-title">Endereços</div>
                    <button class="btn-add">+ Adicionar</button>
                </div>
                <div class="addresses-grid">
                    <div class="address-card">
                        <div class="address-title">Endereço de Entrega Padrão</div>
                    </div>
                    <div class="address-card">
                        <div class="address-title">Endereço de Cobrança Padrão</div>
                    </div>
                </div>
            </div>

            <!-- User Data Section -->
            <div class="user-data-section">
                <div class="user-data-title">Meus Dados</div>
                <div class="user-data-content">
                    <div class="user-data-label">Informações da Conta</div>
                </div>
                <div class="user-data-content">
                    <div class="user-data-value">👤 Greggori</div>
                </div>
                <div class="user-email-box">
                    <span class="email-icon">✉️</span>
                    <span class="email-text">greggori.lol@gmail.com</span>
                </div>
            </div>
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
                    <li><a href="#">Fale Conosco</a></li>
                    <li><a href="#">Chat Suporte</a></li>
                    <li><a href="#">Sua Conta</a></li>
                </div>
            </ul>

            <ul>
                <h3>SERVIÇOS</h3>
                <div class="links">
                    <li><a href="#">Catálogo</a></li>
                    <li><a href="#">Suporte</a></li>
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

    <script src="perfil.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>
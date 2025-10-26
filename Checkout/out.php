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
    <link rel="stylesheet" href="out.css">
    <title>Checkout</title>
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
            <div class="divpesquisar">
                <button id="pesquisar" class="btn-pesquisar"><ion-icon name="search-sharp"></ion-icon></button>
                <input type="text" placeholder=" Pesquisar..." class="barra-pesquisa">
            </div>
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
            <li><a href="#">OFERTAS</a> <ion-icon class="navicon" name="pricetags-outline"></ion-icon> </li>
            <span class="linha"></span>
            <li><a href="../MontarPC/montarpc.php">MONTE SEU PC</a> <ion-icon class="navicon" name="desktop-outline"></ion-icon> </li>
            <span class="linha"></span>
            <li><a href="#">GAMER</a> <ion-icon class="navicon" name="game-controller-outline"></ion-icon> </li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a> <ion-icon class="navicon" name="business-outline"></ion-icon>
            </li>
        </ul>
    </nav>

    <?php show_flash(); ?>

    <div class="container">
        <!-- Seção Principal - Métodos de Pagamento -->
        <div class="main-section">
            <div class="payment-section">
                <div class="section-header">
                    <svg class="icon-card" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-width="2" />
                    </svg>
                    <h2>Método de Pagamento</h2>
                </div>

                <!-- PIX -->
                <div class="payment-option">
                    <label class="payment-label">
                        <input type="radio" name="payment" value="pix" class="payment-radio">
                        <span class="radio-custom"></span>
                        <span class="payment-text">PAGUE VIA PIX</span>
                        <div class="payment-icon pix-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32">
                                <path d="M16 2L2 16L16 30L30 16L16 2Z" fill="#32BCAD" />
                            </svg>
                        </div>
                    </label>
                    <div class="payment-details" id="pix-details">
                        <div class="pix-content">
                            <p>Escaneie o QR Code ou copie o código PIX para realizar o pagamento.</p>
                            <div class="qr-code-placeholder">
                                <div class="qr-grid"></div>
                            </div>
                            <button class="copy-code-btn">Copiar código PIX</button>
                        </div>
                    </div>
                </div>

                <!-- Boleto Bancário -->
                <div class="payment-option">
                    <label class="payment-label">
                        <input type="radio" name="payment" value="boleto" class="payment-radio">
                        <span class="radio-custom"></span>
                        <span class="payment-text">BOLETO BANCÁRIO</span>
                        <div class="payment-icon boleto-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32">
                                <rect width="32" height="32" rx="4" fill="#9CA3AF" />
                                <rect x="6" y="10" width="2" height="12" fill="white" />
                                <rect x="10" y="10" width="2" height="12" fill="white" />
                                <rect x="14" y="10" width="2" height="12" fill="white" />
                                <rect x="18" y="10" width="2" height="12" fill="white" />
                                <rect x="22" y="10" width="2" height="12" fill="white" />
                            </svg>
                        </div>
                    </label>
                    <div class="payment-details" id="boleto-details">
                        <div class="boleto-content">
                            <p>O boleto será gerado após a confirmação do pedido.</p>
                            <p class="boleto-info">Prazo de pagamento: 3 dias úteis</p>
                        </div>
                    </div>
                </div>

                <!-- Cartão de Crédito -->
                <div class="payment-option">
                    <label class="payment-label">
                        <input type="radio" name="payment" value="credit-card" class="payment-radio" checked>
                        <span class="radio-custom"></span>
                        <span class="payment-text">CARTÃO DE CRÉDITO</span>
                        <div class="card-brands">
                            <img src="data:image/svg+xml,%3Csvg width='40' height='24' viewBox='0 0 40 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='40' height='24' rx='3' fill='%23E8E8E8'/%3E%3Ccircle cx='14' cy='12' r='6' fill='%23EB001B'/%3E%3Ccircle cx='26' cy='12' r='6' fill='%23F79E1B'/%3E%3C/svg%3E"
                                alt="Mastercard">
                            <img src="data:image/svg+xml,%3Csvg width='40' height='24' viewBox='0 0 40 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='40' height='24' rx='3' fill='%231434CB'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='Arial' font-weight='bold' font-size='10'%3EVISA%3C/text%3E%3C/svg%3E"
                                alt="Visa">
                            <img src="data:image/svg+xml,%3Csvg width='40' height='24' viewBox='0 0 40 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='40' height='24' rx='3' fill='%23006FCF'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='Arial' font-weight='bold' font-size='8'%3EAMEX%3C/text%3E%3C/svg%3E"
                                alt="Amex">
                            <img src="data:image/svg+xml,%3Csvg width='40' height='24' viewBox='0 0 40 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='40' height='24' rx='3' fill='%23FF6000'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='Arial' font-weight='bold' font-size='7'%3EELO%3C/text%3E%3C/svg%3E"
                                alt="Elo">
                            <img src="data:image/svg+xml,%3Csvg width='40' height='24' viewBox='0 0 40 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='40' height='24' rx='3' fill='%23000000'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='Arial' font-weight='bold' font-size='6'%3EHIPER%3C/text%3E%3C/svg%3E"
                                alt="Hipercard">
                            <img src="data:image/svg+xml,%3Csvg width='40' height='24' viewBox='0 0 40 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='40' height='24' rx='3' fill='%23808080'/%3E%3C/svg%3E"
                                alt="Outros">
                        </div>
                    </label>
                    <div class="payment-details active" id="credit-card-details">
                        <div class="ssl-notice">
                            <svg class="lock-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <rect x="4" y="7" width="8" height="6" rx="1" fill="#10B981" />
                                <path d="M5 7V5C5 3.34315 6.34315 2 8 2C9.65685 2 11 3.34315 11 5V7" stroke="#10B981"
                                    stroke-width="2" />
                            </svg>
                            <span>O nosso site utiliza o protocolo de encriptação SSL. Seu pagamento está seguro</span>
                        </div>

                        <form class="credit-card-form" id="credit-card-form">
                            <div class="form-row">
                                <div class="form-group cvv-group">
                                    <input type="text" id="cvv" placeholder="CVV *" maxlength="4" required>
                                    <svg class="card-icon-small" width="32" height="20" viewBox="0 0 32 20">
                                        <rect width="32" height="20" rx="2" fill="#FFD700" />
                                        <rect x="2" y="4" width="28" height="3" fill="#000" />
                                    </svg>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <select id="month" required>
                                        <option value="">Mês Validade *</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select id="year" required>
                                        <option value="">Ano Validade *</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                        <option value="2031">2031</option>
                                        <option value="2032">2032</option>
                                        <option value="2033">2033</option>
                                        <option value="2034">2034</option>
                                        <option value="2035">2035</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group full-width">
                                    <input type="text" id="card-number" placeholder="Número do cartão *" maxlength="19"
                                        required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group full-width">
                                    <input type="text" id="card-name" placeholder="Nome do titular do cartão *"
                                        required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group full-width">
                                    <input type="text" id="cpf" placeholder="9 x de R$ 24,88 (com 5% de desconto)"
                                        required>
                                </div>
                            </div>

                            <button type="submit" class="submit-btn">Confirmar e seguir para a entrega</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Direita -->
        <div class="sidebar">
            <!-- Resumo -->
            <div class="summary-card">
                <h3>RESUMO</h3>
                <div class="product-info">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Captura%20de%20tela%202025-10-20%20082254-Oj26I4mxtsOYIqCelXp3wG4OWvOknw.png"
                        alt="Placa de Vídeo" class="product-image">
                    <div class="product-details">
                        <p class="product-name">Placa de Vídeo Gigabyte Aorus AMD Radeon RX 9070 XT Elite, 16GB, GDDR6,
                            FSR, Ray Tracing, GV-R907XTAORUS E-16GD</p>
                    </div>
                </div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span class="price">R$ XXX,XX</span>
                </div>
                <div class="summary-row">
                    <span>Desconto</span>
                    <span class="price discount">R$ XXX,XX</span>
                </div>
                <div class="summary-row total-row">
                    <span class="total-label">Total</span>
                    <span class="total-price">R$ XXX,XX</span>
                </div>

                <div class="qr-code-section">
                    <div class="qr-code-small"></div>
                    <a href="#" class="vista-link">À vista</a>
                </div>
            </div>

            <!-- Frete -->
            <div class="shipping-card">
                <h3>FRETE</h3>
                <div class="shipping-form">
                    <input type="text" id="cep" placeholder="CEP*" maxlength="9">
                    <button type="button" id="calculate-shipping" class="calculate-btn">Calcular</button>
                </div>
                <div class="shipping-options" id="shipping-options"></div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container-footer">
            <ul>
                <h3>TECHFORGE</h3>
                <div class="links">
                    <li><a href="../Sobre/sobre.php">Sobre nós</a></li>
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

    <script src="../Comum/common.js"></script>
    <script src="out.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>

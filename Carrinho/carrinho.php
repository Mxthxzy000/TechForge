<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="carrinho.css">
    <title>Carrinho</title>
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

    <div class="container">
        <div class="cart-section">
            <div class="cart-header">
                <svg class="cart-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <h1>MEU CARRINHO</h1>
            </div>

            <div class="cart-items" id="cartItems">
                <!-- Items will be dynamically inserted here -->
            </div>
        </div>

        <div class="summary-box">
            <h2 class="summary-title">RESUMO</h2>
            
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value" id="subtotal">R$ 0,00</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Desconto</span>
                <span class="summary-value" id="discount">R$ 0,00</span>
            </div>
            
            <div class="summary-total">
                <span class="total-label">Total</span>
                <span class="total-value" id="total">R$ 0,00</span>
            </div>

            <div class="qr-section">
                <div class="qr-code">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <rect width="60" height="60" fill="white"/>
                        <rect x="5" y="5" width="10" height="10" fill="black"/>
                        <rect x="20" y="5" width="5" height="5" fill="black"/>
                        <rect x="30" y="5" width="5" height="5" fill="black"/>
                        <rect x="45" y="5" width="10" height="10" fill="black"/>
                        <rect x="5" y="20" width="5" height="5" fill="black"/>
                        <rect x="25" y="20" width="10" height="10" fill="black"/>
                        <rect x="50" y="20" width="5" height="5" fill="black"/>
                        <rect x="5" y="30" width="5" height="5" fill="black"/>
                        <rect x="20" y="30" width="5" height="5" fill="black"/>
                        <rect x="50" y="30" width="5" height="5" fill="black"/>
                        <rect x="5" y="45" width="10" height="10" fill="black"/>
                        <rect x="30" y="45" width="5" height="5" fill="black"/>
                        <rect x="45" y="45" width="10" height="10" fill="black"/>
                    </svg>
                </div>
                <div class="payment-text">À vista</div>
                <div class="payment-subtext">No pix você ganha 5% de desconto</div>
            </div>

            <button class="checkout-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                FINALIZAR PEDIDO
            </button>
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

    <script src="carrinho.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
<?php
include "../config.php";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
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
            <li><a href="../Home/index.php"><ion-icon name="return-down-back-outline"></ion-icon>INÍCIO </a></li>
        </ul>
    </nav>

    <h1 class="login-title">Login</h1>

    <div class="container">
        <!-- Left side - Banner -->
        <div class="banner-section">
            <img src="../imagens/image-pc.webp" alt="Banner Image" class="banner-image">
        </div>

        <!-- Right side - Login Form -->
        <div class="login-section">
            <div class="login-form-container">
                <form id="loginForm" class="login-form">
                    <div class="input-group">
                        <label for="email" class="input-label">E-mail</label>
                        <input type="email" id="email" name="email" class="input-field" required>
                    </div>

                    <div class="input-group">
                        <label for="password" class="input-label">Senha</label>
                        <input type="password" id="password" name="password" class="input-field" required>
                    </div>
                    <span class="span"></span>
                    <div class="login-buttons">
                        <button type="submit" class="login-button">Entrar</button>
                        <button type="button" class="forgot-password-button" id="cadastre-se">Cadastre-se</button>
                    </div>

                </form>
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

    <script src="login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>
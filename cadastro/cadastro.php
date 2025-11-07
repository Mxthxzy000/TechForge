<?php
require "../config.php";
require "../session.php";
require "../flash.php";

if (!empty($_SESSION['idUsuario'])) {
    header('Location: ../Home/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="cadastro.css">
    <title>Cadastro - TechForge</title>
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
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a> <ion-icon class="navicon" name="business-outline"></ion-icon>
            </li>
        </ul>
    </nav>

    <?php show_flash(); ?>
    <h1 class="login-title">Cadastro</h1>

    <div class="container">
        <div class="banner-section">
            <img src="../imagens/image.webp" alt="Banner Image" class="banner-image">
        </div>

        <div class="login-section">
            <div class="login-form-container">
                <form id="loginForm" class="login-form" action="addNovoAluno.php" method="POST">
                    <div class="input-group">
                        <input type="text" id="nome" name="nomeUsuario" class="input-field" required placeholder="Nome"
                            value="<?php echo isset($_POST['nomeUsuario']) ? htmlspecialchars($_POST['nomeUsuario']) : ''; ?>">
                        <input type="text" id="Sobrenome" name="sobrenomeUsuario" class="input-field" required
                            placeholder="Sobrenome"
                            value="<?php echo isset($_POST['sobrenomeUsuario']) ? htmlspecialchars($_POST['sobrenomeUsuario']) : ''; ?>">
                    </div>

                    <div class="input-group">
                        <input type="text" id="nascimento" name="nascimentoUsuario" class="input-field"
                            placeholder="Data de Nascimento" maxlength="10"
                            value="<?php echo isset($_POST['nascimentoUsuario']) ? htmlspecialchars($_POST['nascimentoUsuario']) : ''; ?>">
                        <input type="text" id="celular" name="celularUsuario" class="input-field" maxlength="15"
                            placeholder="Celular"
                            value="<?php echo isset($_POST['celularUsuario']) ? htmlspecialchars($_POST['celularUsuario']) : ''; ?>">
                    </div>

                    <div class="input-group-max">
                        <input type="email" id="email" name="emailUsuario" class="input-field" required
                            placeholder="E-mail"
                            value="<?php echo isset($_POST['emailUsuario']) ? htmlspecialchars($_POST['emailUsuario']) : ''; ?>">
                    </div>

                    <div class="input-group">
                        <input type="password" id="senhaUsuario1" name="senhaUsuario1" class="input-field" required
                            placeholder="Crie sua Senha">
                        <input type="password" id="senhaUsuario" name="senhaUsuario" class="input-field" required
                            placeholder="Confirme sua Senha">
                    </div>

                    <div class="terms-text">
                        <p>Ao criar a conta, declaro que li, entendi e aceito os <a href="#" class="terms-link">Termos
                                de serviço</a> e as <a href="#" class="terms-link">Políticas de privacidade</a> da
                            TechForge</p>
                    </div>

                    <div class="login-buttons">
                        <button type="submit" class="login-button">Cadastre-se</button>
                        <button type="button" class="forgot-password-button" id="fazerlogin">Login</button>
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

    <!-- Incluindo script comum e específico -->
    <script src="../Comum/common.js"></script>
    <script src="cadastro.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
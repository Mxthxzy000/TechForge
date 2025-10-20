<?php
require '../config.php';
require '../session.php';
require '../flash.php';

if (empty($_SESSION['idUsuario'])) {
    set_flash('erro', 'Você precisa fazer login para acessar esta página.');
    header('Location: ../Login/login.php');
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$sql = "SELECT nomeUsuario, sobrenomeUsuario, emailUsuario, fotoUsuario, celularUsuario, nascimentoUsuario 
        FROM usuario 
        WHERE idUsuario = ? 
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    set_flash('erro', 'Usuário não encontrado.');
    header('Location: ../Login/login.php');
    exit;
}

$nomeCompleto = trim($usuario['nomeUsuario'] . ' ' . ($usuario['sobrenomeUsuario'] ?? ''));
$email = $usuario['emailUsuario'];
$foto = $usuario['fotoUsuario'] ?? '../imagens/default-avatar.png';
$celular = $usuario['celularUsuario'] ?? 'Não informado';
$nascimento = $usuario['nascimentoUsuario'] ?? 'Não informado';

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="perfil.css">
    <title>Perfil - TechForge</title>
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
             <li><a href="../Home/index.php">HOME</a></li>
            <span class="linha"></span>
            <li><a href="../Catalogo/catalogo.php">PRODUTOS</a></li>
            <span class="linha"></span>
            <li><a href="#">OFERTAS</a></li>
            <span class="linha"></span>
            <li><a href="#">MONTE SEU PC</a></li>
            <span class="linha"></span>
            <li><a href="#">GAMER</a></li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a></li>
        </ul>
    </nav>

    <?php show_flash(); ?>

    <div class="container-new">
        <!-- Header com dados reais do usuário -->
        <div class="header-new">
            <div class="user-info">
                <div class="user-avatar">
                    <?php if ($foto !== '../imagens/default-avatar.png'): ?>
                        <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto do usuário"
                            style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                    <?php else: ?>
                        👤
                    <?php endif; ?>
                </div>
                <div class="user-details">
                    <h2>Olá, <?php echo htmlspecialchars($nomeCompleto); ?></h2>
                    <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
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

            <!-- Mensagem quando não há pedidos -->
            <div class="order-card">
                <p style="text-align:center;padding:20px;color:#666;">Você ainda não fez nenhum pedido.</p>
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
                        <p style="color:#666;font-size:14px;margin-top:10px;">Nenhum endereço cadastrado</p>
                    </div>
                    <div class="address-card">
                        <div class="address-title">Endereço de Cobrança Padrão</div>
                        <p style="color:#666;font-size:14px;margin-top:10px;">Nenhum endereço cadastrado</p>
                    </div>
                </div>
            </div>

            <!-- User Data Section com dados reais -->
            <div class="user-data-section">
                <div class="user-data-title">Meus Dados</div>
                <div class="user-data-content">
                    <div class="user-data-label">Informações da Conta</div>
                </div>
                <div class="user-data-content">
                    <div class="user-data-value">👤 <?php echo htmlspecialchars($nomeCompleto); ?></div>
                </div>
                <div class="user-email-box">
                    <span class="email-icon">✉️</span>
                    <span class="email-text"><?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="user-data-content" style="margin-top:10px;">
                    <div class="user-data-value">📱 <?php echo htmlspecialchars($celular); ?></div>
                </div>
                <div class="user-data-content" style="margin-top:10px;">
                    <div class="user-data-value">🎂 <?php echo htmlspecialchars($nascimento); ?></div>
                </div>
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

    <!-- Incluindo script comum e específico -->
    <script src="../Comum/common.js"></script>
    <script src="perfil.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
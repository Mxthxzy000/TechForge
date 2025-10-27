<?php
require '../config.php';
require '../session.php';
require '../flash.php';

if (!isset($conn)) {
    die("Erro: Conexão com banco de dados não estabelecida.");
}

// Pegar o ID do produto da URL
$idProduto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idProduto <= 0) {
    header('Location: ../Catalogo/catalogo.php');
    exit;
}

// Buscar informações do produto
$sql = "SELECT * FROM produtos WHERE idProduto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idProduto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../Catalogo/catalogo.php');
    exit;
}

$produto = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nomeProduto']); ?> | TechForge</title>
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="detalhes.css">
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>
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
                <input type="text" id="searchInput" placeholder=" Pesquisar..." class="barra-pesquisa">
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="../Home/index.php">Home</a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <a href="../Catalogo/catalogo.php">Catálogo</a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <span><?php echo htmlspecialchars($produto['nomeProduto']); ?></span>
    </div>

    <div class="container-detalhes">
        <!-- Seção de Imagem com Zoom -->
        <div class="produto-imagem-container">
            <div class="imagem-principal">
                <img src="../imagens_produtos/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                     alt="<?php echo htmlspecialchars($produto['nomeProduto']); ?>"
                     id="produtoImagem">
            </div>
        </div>

        <!-- Informações do Produto -->
        <div class="produto-info">
            <h1 class="produto-titulo"><?php echo htmlspecialchars($produto['nomeProduto']); ?></h1>
            
            <!-- Tipo e Linha -->
            <div class="produto-meta">
                <span class="meta-badge tipo-badge">
                    <ion-icon name="cube-outline"></ion-icon>
                    <?php echo htmlspecialchars($produto['tipoProduto']); ?>
                </span>
                <?php if (!empty($produto['linhaProduto'])): ?>
                    <span class="meta-badge linha-badge">
                        <ion-icon name="hardware-chip-outline"></ion-icon>
                        <?php echo htmlspecialchars($produto['linhaProduto']); ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Preço -->
            <div class="produto-preco">
                <p class="preco-principal">
                    R$ <?php echo number_format($produto['valorProduto'], 2, ',', '.'); ?>
                    <span class="preco-avista">à vista</span>
                </p>
                <p class="preco-parcelado">
                    ou 12x de R$ <?php echo number_format($produto['valorProduto'] / 12, 2, ',', '.'); ?> sem juros
                </p>
            </div>

            <!-- Descrição -->
            <div class="produto-descricao">
                <h3><ion-icon name="document-text-outline"></ion-icon> Descrição</h3>
                <p><?php echo nl2br(htmlspecialchars($produto['descricaoProduto'])); ?></p>
            </div>

            <!-- Tags -->
            <?php if (!empty($produto['tagsProduto'])): ?>
                <div class="produto-tags">
                    <h3><ion-icon name="pricetags-outline"></ion-icon> Tags</h3>
                    <div class="tags-lista">
                        <?php
                        $tags = explode(',', $produto['tagsProduto']);
                        foreach ($tags as $tag):
                            $tag = trim($tag);
                            if (!empty($tag)):
                        ?>
                            <span class="tag-item"><?php echo htmlspecialchars($tag); ?></span>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Estoque -->
            <div class="produto-estoque">
                <?php if ($produto['quantidadeProduto'] > 0): ?>
                    <span class="estoque-disponivel">
                        <ion-icon name="checkmark-circle"></ion-icon>
                        <?php echo $produto['quantidadeProduto']; ?> unidades em estoque
                    </span>
                <?php else: ?>
                    <span class="estoque-indisponivel">
                        <ion-icon name="close-circle"></ion-icon>
                        Produto indisponível
                    </span>
                <?php endif; ?>
            </div>

            <!-- Botões de Ação -->
            <div class="produto-acoes">
                <button class="btn-adicionar-carrinho" id="btnAdicionarCarrinho" 
                        data-id="<?php echo $produto['idProduto']; ?>"
                        <?php echo $produto['quantidadeProduto'] <= 0 ? 'disabled' : ''; ?>>
                    <ion-icon name="cart-outline"></ion-icon>
                    Adicionar ao Carrinho
                </button>
                <button class="btn-voltar" onclick="window.history.back()">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    Voltar
                </button>
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
    <script src="detalhes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>

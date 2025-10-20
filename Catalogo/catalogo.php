<?php
include '../config.php';
include '../session.php';
include '../flash.php';

if (!isset($conn) || $conn->connect_error) {
    die("Erro de conexão com banco de dados");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo | TechForge</title>
    <link rel="stylesheet" href="catalogo.css">
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>
</head>

<body>
    <header>
        <div class="inicio-header">
            <div class="hamburguer-menu"><span></span><span></span><span></span></div>
            <img src="../imagens/logo_header_TechForge.png" alt="TechForge Logo" class="logo">
        </div>
        <div class="final-header">
            <div class="divpesquisar">
                <button class="btn-pesquisar"><ion-icon name="search-sharp"></ion-icon></button>
                <input type="text" id="searchInput" class="barra-pesquisa" placeholder="Pesquisar produtos..." autocomplete="off">
                <ul id="suggestions" class="suggestions-list"></ul>
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
            <a href="../Perfil/perfil.php" class="menu-usuario" style="justify-content: space-between; align-items: center;">
                <span>Olá, <?php echo htmlspecialchars($_SESSION['nomeUsuario']); ?>...</span>
                <?php if (!empty($_SESSION['fotoUsuario'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['fotoUsuario']); ?>" alt="Foto do Usuário" class="foto-usuario"
                        style="width:26px;height:26px;border-radius:50%;object-fit:cover;">
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
            <li><a href="../Home/index.php">INÍCIO <ion-icon name="home-outline"></ion-icon></a></li>
            <span class="linha"></span>
            <li><a href="catalogo.php?categoria=promocoes" id="link-promocoes">OFERTAS</a></li>
            <span class="linha"></span>
            <li><a href="#">MONTE SEU PC</a></li>
            <span class="linha"></span>
            <li><a href="catalogo.php?categoria=gamer" id="link-gamer">GAMER</a></li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a></li>
        </ul>
    </nav>

    <?php show_flash(); ?>

    <div class="container-catalogo">
        <aside class="filtro-lateral">
            <h2>Filtros</h2>
            <div class="filtro-secao">
                <h3>Preço:</h3>
                <div class="preco-inputs">
                    <input type="number" id="priceMin" placeholder="Min R$">
                    <input type="number" id="priceMax" placeholder="Max R$">
                </div>
                <button id="applyPrice" class="btn-filtrar">Aplicar</button>
            </div>

            <div class="filtro-secao">
                <h3>Linhas:</h3>
                <div class="linha-marcas">
                    <div class="marca-option botao-filtro" data-linha="Intel"><img src="../imagens/intel-logo.png" alt="Intel"></div>
                    <div class="marca-option botao-filtro" data-linha="AMD"><img src="../imagens/AMD-logo.png" alt="AMD"></div>
                </div>
                <div class="tipos-pecas">
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Processador"> Processadores</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Placa de Vídeo"> Placas de Vídeo</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Placa-mãe"> Placas-Mãe</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Memória RAM"> Memórias RAM</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Cooler"> Coolers</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Fonte"> Fontes</label>
                </div>
            </div>
        </aside>

        <main class="produtos-lista">
            <div class="cabecalho-lista">
                <h2 id="pageTitle">Produtos</h2>
                <span id="resultCount"></span>
            </div>
            <div id="products" class="cards-produtos">
                <p>Carregando produtos...</p>
            </div>
        </main>
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
        </div>
    </footer>

    <script src="../js/common.js"></script>
    <script src="catalogo.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

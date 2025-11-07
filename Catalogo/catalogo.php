<?php
require '../config.php';
require '../session.php';
require '../flash.php';

if (!isset($conn)) {
    die("Erro: Conexão com banco de dados não estabelecida.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo | TechForge</title>
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="catalogo.css">
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
            <li><a href="../MontarPC/montarpc.php">MONTE SEU PC</a> <ion-icon class="navicon" name="desktop-outline"></ion-icon> </li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a> <ion-icon class="navicon" name="business-outline"></ion-icon></li>
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
                    <div class="marca-option botao-filtro" data-linha="Intel"><img src="../imagens/intel-logo.png"
                            alt="Intel"></div>
                    <div class="marca-option botao-filtro" data-linha="AMD"><img src="../imagens/AMD-logo.png"
                            alt="AMD"></div>
                </div>
                <div class="tipos-pecas">
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Processador">
                        Processadores</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Placa de Vídeo"> Placas de
                        Vídeo</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Placa-mãe">
                        Placas-Mãe</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Memória RAM"> Memórias
                        RAM</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Cooler"> Coolers</label>
                    <label><input type="checkbox" class="tipoFiltro botao-filtro" data-tag="Fonte"> Fontes</label>
                </div>
            </div>

            <!-- Dynamic tags section with search -->
            <div class="filtro-secao">
                <h3>Tags Populares:</h3>
                
                <!-- Tag search input -->
                <div class="tag-search-container">
                    <input type="text" id="tagSearchInput" placeholder="Buscar tag..." class="tag-search-input">
                    <ion-icon name="search-outline" class="tag-search-icon"></ion-icon>
                </div>
                
                <div id="filterTags" class="tags-container">
                    <?php
                    // Buscar tags populares do banco de dados
                    $sql = "SELECT tagsProduto FROM produtos WHERE tagsProduto IS NOT NULL AND tagsProduto != ''";
                    $res = $conn->query($sql);
                    
                    $all_tags = [];
                    if ($res && $res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            $tags = explode(',', $row['tagsProduto']);
                            foreach ($tags as $tag) {
                                $clean_tag = trim($tag);
                                if (!empty($clean_tag)) {
                                    $all_tags[] = $clean_tag;
                                }
                            }
                        }
                    }
                    
                    // Contar frequência e pegar as 10 mais populares
                    $tag_count = array_count_values($all_tags);
                    arsort($tag_count);
                    $popularTags = array_slice($tag_count, 0, 10, true);
                    
                    // Exibir as 10 tags mais populares
                    foreach ($popularTags as $tag => $count): ?>
                        <span class="tag-option botao-filtro" data-tag="<?php echo htmlspecialchars($tag); ?>">
                            <?php echo htmlspecialchars($tag); ?> (<?php echo $count; ?>)
                        </span>
                    <?php endforeach; ?>
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

    <script src="../Comum/common.js"></script>
    <script src="catalogo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- incluir script de notificação centralizado -->
    <script src="../js/notification.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>

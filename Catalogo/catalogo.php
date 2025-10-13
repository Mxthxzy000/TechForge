<?php
include '../config.php';
include '../session.php';
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
                    <button class="btn-pesquisar"><ion-icon name="search-sharp"></ion-icon></button>
                    <input type="text" id="searchInput" class="barra-pesquisa" placeholder="Pesquisar produtos..."
                        autocomplete="off">
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
                <!-- Linha do nome do usuário como botão -->
                <a href="../Home/perfil.php" class="menu-usuario"
                    style="justify-content: space-between; align-items: center;">
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['nomeUsuario']); ?>...</span>

                    <?php if (!empty($_SESSION['fotoUsuario'])): ?>
                        <img src="<?php echo $_SESSION['fotoUsuario']; ?>" alt="Foto do Usuário" class="foto-usuario"
                            style="width:26px;height:26px;border-radius:50%;object-fit:cover;">
                    <?php else: ?>
                        <ion-icon name="person-circle-outline" class="icon-user"></ion-icon>
                    <?php endif; ?>
                </a>

                <div class="menu-usuario" style="display:flex;align-items:center;gap:8px;">
                    <span>Modo escuro:</span>
                    <label class="switch">
                        <input type="checkbox" id="botao-modo-escuro">
                        <span class="slider-dark"></span>
                    </label>
                </div>

                <form method="POST" action="../logout.php">
                    <button type="submit" class="menu-usuario">
                        Sair
                        <ion-icon name="log-out-outline" class="icon-user"></ion-icon>
                    </button>
                </form>

            <?php else: ?>
                <a href="../Login/login.php" class="menu-usuario">
                    Fazer Login!
                    <ion-icon name="log-in-outline" class="icon-user"></ion-icon>
                </a>

                <div class="menu-usuario" style="display:flex;align-items:center;gap:8px;">
                    <span>Modo escuro:</span>
                    <label class="switch">
                        <input type="checkbox" id="botao-modo-escuro">
                        <span class="slider-dark"></span>
                    </label>
                </div>
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
                <li><a href="#">SOBRE NÓS</a></li>
            </ul>
        </nav>

        <div class="container-catalogo">
            <!-- FILTRO LATERAL -->
            <aside class="filtro-lateral">
                <h2>Filtros</h2>

                <div class="filtro-secao">
                    <h3>Preço:</h3>
                    <div class="preco-inputs">
                        <input type="number" id="priceMin" placeholder="Min R$">
                        <input type="number" id="priceMax" placeholder="Max R$">
                        <button id="applyPrice" class="btn-filtrar">Aplicar</button>
                    </div>
                </div>

                <div class="filtro-secao">
                    <h3>Linhas:</h3>
                    <div id="filterLines">Carregando...</div>
                </div>

                <div class="filtro-secao">
                    <h3>Tags populares:</h3>
                    <div id="filterTags">Carregando...</div>
                </div>

                <div class="filtro-secao">
                    <label><input type="checkbox" id="onlyPromos"> Mostrar apenas promoções</label>
                </div>
            </aside>

            <!-- PRODUTOS -->
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
        <script src="catalogo.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>

</html>
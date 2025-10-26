<?php
require '../config.php';
require '../session.php';
require '../flash.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="montarpc.css">
    <title>Monte Seu PC - TechForge</title>
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
            <a href="../Perfil/perfil.php" class="menu-usuario" style="justify-content: space-between; align-items: center;">
                <span>Olá, <?php echo htmlspecialchars($_SESSION['nomeUsuario']); ?>...</span>
                <?php if (!empty($_SESSION['fotoUsuario'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['fotoUsuario']); ?>" alt="Foto do Usuário" class="foto-usuario" style="width:26px;height:26px;border-radius:50%;object-fit:cover;">
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
            <li><a href="../MontarPC/montarpc.php">MONTE SEU PC</a></li>
            <span class="linha"></span>
            <li><a href="#">GAMER</a></li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a></li>
        </ul>
    </nav>

    <?php show_flash(); ?>

    <div class="container-builder">
        <div class="builder-header">
            <h1>Monte Seu PC Personalizado</h1>
            <p>Selecione os componentes e monte o PC dos seus sonhos</p>
        </div>

        <div class="builder-content">
            <div class="components-section">
                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="hardware-chip-outline"></ion-icon>
                        <h3>Processador (CPU)</h3>
                    </div>
                    <select id="cpu" class="component-select">
                        <option value="">Selecione um processador</option>
                    </select>
                    <div class="component-price" id="cpu-price">R$ 0,00</div>
                </div>

                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="desktop-outline"></ion-icon>
                        <h3>Placa de Vídeo (GPU)</h3>
                    </div>
                    <select id="gpu" class="component-select">
                        <option value="">Selecione uma placa de vídeo</option>
                    </select>
                    <div class="component-price" id="gpu-price">R$ 0,00</div>
                </div>

                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="grid-outline"></ion-icon>
                        <h3>Placa-Mãe</h3>
                    </div>
                    <select id="placaMae" class="component-select">
                        <option value="">Selecione uma placa-mãe</option>
                    </select>
                    <div class="component-price" id="placaMae-price">R$ 0,00</div>
                </div>

                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="albums-outline"></ion-icon>
                        <h3>Memória RAM</h3>
                    </div>
                    <select id="ram" class="component-select">
                        <option value="">Selecione a memória RAM</option>
                    </select>
                    <div class="component-price" id="ram-price">R$ 0,00</div>
                </div>

                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="save-outline"></ion-icon>
                        <h3>Armazenamento (SSD/HD)</h3>
                    </div>
                    <select id="armazenamento" class="component-select">
                        <option value="">Selecione o armazenamento</option>
                    </select>
                    <div class="component-price" id="armazenamento-price">R$ 0,00</div>
                </div>

                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="flash-outline"></ion-icon>
                        <h3>Fonte de Alimentação</h3>
                    </div>
                    <select id="fonte" class="component-select">
                        <option value="">Selecione a fonte</option>
                    </select>
                    <div class="component-price" id="fonte-price">R$ 0,00</div>
                </div>

                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="cube-outline"></ion-icon>
                        <h3>Gabinete</h3>
                    </div>
                    <select id="gabinete" class="component-select">
                        <option value="">Selecione o gabinete</option>
                    </select>
                    <div class="component-price" id="gabinete-price">R$ 0,00</div>
                </div>

                <div class="component-card">
                    <div class="component-header">
                        <ion-icon name="snow-outline"></ion-icon>
                        <h3>Cooler (Opcional)</h3>
                    </div>
                    <select id="cooler" class="component-select">
                        <option value="">Selecione o cooler</option>
                    </select>
                    <div class="component-price" id="cooler-price">R$ 0,00</div>
                </div>
            </div>

            <div class="summary-section">
                <div class="summary-card">
                    <h2>Resumo da Montagem</h2>
                    
                    <div class="setup-name-input">
                        <label>Nome do Setup</label>
                        <input type="text" id="nomeSetup" placeholder="Ex: PC Gamer 2025" maxlength="100">
                    </div>

                    <div class="summary-list" id="summaryList">
                        <p style="color: #666; text-align: center; padding: 20px;">Nenhum componente selecionado</p>
                    </div>

                    <div class="observations">
                        <label>Observações (Opcional)</label>
                        <textarea id="observacoes" placeholder="Adicione observações sobre sua montagem..." rows="4"></textarea>
                    </div>

                    <div class="total-price">
                        <span>Total Estimado:</span>
                        <span id="totalPrice">R$ 0,00</span>
                    </div>

                    <button class="btn-save-build" id="saveBuildBtn">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                        Salvar Montagem
                    </button>

                    <p class="info-text">Sua montagem será salva e você poderá visualizá-la no seu perfil</p>
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

    <script src="../Comum/common.js"></script>
    <script src="montarpc.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

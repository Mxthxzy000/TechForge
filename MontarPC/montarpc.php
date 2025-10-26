<?php
require '../config.php';
require '../session.php';
require '../flash.php';

// Get selected components from session
if (!isset($_SESSION['pc_build'])) {
    $_SESSION['pc_build'] = [
        'cpu' => null,
        'gpu' => null,
        'placaMae' => null,
        'ram' => null,
        'armazenamento' => null,
        'fonte' => null,
        'gabinete' => null,
        'cooler' => null,
        'nomeSetup' => '',
        'observacoes' => ''
    ];
}

$build = $_SESSION['pc_build'];
$totalPrice = 0;

// Calculate total price
foreach ($build as $key => $component) {
    if ($component && isset($component['price'])) {
        $totalPrice += $component['price'];
    }
}
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
            <li><a href="../Home/index.php">HOME</a> <ion-icon class="navicon" name="home-outline"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="../Catalogo/catalogo.php">PRODUTOS</a> <ion-icon name="bag-outline" class="navicon"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="#">OFERTAS</a> <ion-icon class="navicon" name="pricetags-outline"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="#">GAMER</a> <ion-icon class="navicon" name="game-controller-outline"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a> <ion-icon class="navicon" name="business-outline"></ion-icon></li>
        </ul>
    </nav>

    <?php show_flash(); ?>

    <div class="container-builder">
        <div class="builder-header">
            <h1>Monte Seu PC Personalizado</h1>
            <p>Escolha cada componente e monte o PC perfeito para você</p>
        </div>

        <div class="builder-content">
            <div class="components-grid">
                <!-- CPU Card -->
                <a href="selecionar-componente.php?tipo=processador" class="component-selection-card">
                    <div class="component-icon">
                        <ion-icon name="hardware-chip-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Processador (CPU)</h3>
                        <?php if ($build['cpu']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['cpu']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['cpu']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>

                <!-- GPU Card -->
                <a href="selecionar-componente.php?tipo=placa-video" class="component-selection-card">
                    <div class="component-icon">
                        <ion-icon name="desktop-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Placa de Vídeo (GPU)</h3>
                        <?php if ($build['gpu']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['gpu']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['gpu']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>

                <!-- Placa-Mãe Card -->
                <a href="selecionar-componente.php?tipo=placa-mae" class="component-selection-card">
                    <div class="component-icon">
                        <ion-icon name="grid-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Placa-Mãe</h3>
                        <?php if ($build['placaMae']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['placaMae']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['placaMae']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>

                <!-- RAM Card -->
                <a href="selecionar-componente.php?tipo=memoria" class="component-selection-card">
                    <div class="component-icon">
                        <ion-icon name="albums-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Memória RAM</h3>
                        <?php if ($build['ram']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['ram']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['ram']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>

                <!-- Armazenamento Card -->
                <a href="selecionar-componente.php?tipo=armazenamento" class="component-selection-card">
                    <div class="component-icon">
                        <ion-icon name="save-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Armazenamento (SSD/HD)</h3>
                        <?php if ($build['armazenamento']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['armazenamento']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['armazenamento']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>

                <!-- Fonte Card -->
                <a href="selecionar-componente.php?tipo=fonte" class="component-selection-card">
                    <div class="component-icon">
                        <ion-icon name="flash-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Fonte de Alimentação</h3>
                        <?php if ($build['fonte']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['fonte']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['fonte']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>

                <!-- Gabinete Card -->
                <a href="selecionar-componente.php?tipo=gabinete" class="component-selection-card">
                    <div class="component-icon">
                        <ion-icon name="cube-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Gabinete</h3>
                        <?php if ($build['gabinete']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['gabinete']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['gabinete']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>

                <!-- Cooler Card -->
                <a href="selecionar-componente.php?tipo=cooler" class="component-selection-card optional">
                    <div class="component-icon">
                        <ion-icon name="snow-outline"></ion-icon>
                    </div>
                    <div class="component-info">
                        <h3>Cooler <span class="optional-badge">Opcional</span></h3>
                        <?php if ($build['cooler']): ?>
                            <p class="selected-component"><?php echo htmlspecialchars($build['cooler']['name']); ?></p>
                            <p class="component-price-display">R$ <?php echo number_format($build['cooler']['price'], 2, ',', '.'); ?></p>
                        <?php else: ?>
                            <p class="no-selection">Clique para escolher</p>
                        <?php endif; ?>
                    </div>
                    <div class="component-arrow">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </a>
            </div>

            <div class="summary-section">
                <div class="summary-card">
                    <h2>Resumo da Montagem</h2>
                    
                    <div class="setup-name-input">
                        <label>Nome do Setup</label>
                        <input type="text" id="nomeSetup" placeholder="Ex: PC Gamer 2025" maxlength="100" value="<?php echo htmlspecialchars($build['nomeSetup']); ?>">
                    </div>

                    <div class="summary-list" id="summaryList">
                        <?php
                        $hasComponents = false;
                        $componentLabels = [
                            'cpu' => 'Processador',
                            'gpu' => 'Placa de Vídeo',
                            'placaMae' => 'Placa-Mãe',
                            'ram' => 'Memória RAM',
                            'armazenamento' => 'Armazenamento',
                            'fonte' => 'Fonte',
                            'gabinete' => 'Gabinete',
                            'cooler' => 'Cooler'
                        ];

                        foreach ($build as $key => $component) {
                            if ($component && isset($component['name'])) {
                                $hasComponents = true;
                                echo '<div class="summary-item">';
                                echo '<span class="summary-item-name">' . htmlspecialchars($componentLabels[$key]) . '</span>';
                                echo '<span class="summary-item-value">R$ ' . number_format($component['price'], 2, ',', '.') . '</span>';
                                echo '</div>';
                            }
                        }

                        if (!$hasComponents) {
                            echo '<p style="color: #666; text-align: center; padding: 20px;">Nenhum componente selecionado</p>';
                        }
                        ?>
                    </div>

                    <div class="observations">
                        <label>Observações (Opcional)</label>
                        <textarea id="observacoes" placeholder="Adicione observações sobre sua montagem..." rows="4"><?php echo htmlspecialchars($build['observacoes']); ?></textarea>
                    </div>

                    <div class="total-price">
                        <span>Total Estimado:</span>
                        <span id="totalPrice">R$ <?php echo number_format($totalPrice, 2, ',', '.'); ?></span>
                    </div>

                    <button class="btn-save-build" id="saveBuildBtn">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                        Salvar Montagem
                    </button>

                    <button class="btn-clear-build" id="clearBuildBtn">
                        <ion-icon name="trash-outline"></ion-icon>
                        Limpar Montagem
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

<?php
require '../config.php';
require '../session.php';
require '../flash.php';

// Initialize pc_build session structure
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

// Remove invalid session keys that might have been created
$validKeys = ['cpu', 'gpu', 'placaMae', 'ram', 'armazenamento', 'fonte', 'gabinete', 'cooler', 'nomeSetup', 'observacoes'];
foreach ($build as $key => $value) {
    if (!in_array($key, $validKeys)) {
        unset($build[$key]);
    }
}
$_SESSION['pc_build'] = $build;

// Calculate total price
$totalPrice = 0;
foreach ($build as $key => $component) {
    if (is_array($component) && isset($component['price'])) {
        $totalPrice += floatval($component['price']);
    }
}

// Component labels for display
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

// Check if build has required components
$requiredComponents = ['cpu', 'gpu', 'placaMae', 'ram', 'armazenamento', 'fonte', 'gabinete'];
$hasRequiredComponents = true;
foreach ($requiredComponents as $req) {
    if (!isset($build[$req]) || !is_array($build[$req]) || empty($build[$req]['name'])) {
        $hasRequiredComponents = false;
        break;
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
                        <?php if (isset($build['cpu']) && is_array($build['cpu']) && !empty($build['cpu']['name'])): ?>
                            <?php if (!empty($build['cpu']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['cpu']['image']); ?>" alt="CPU">
                                </div>
                            <?php endif; ?>
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
                        <?php if (isset($build['gpu']) && is_array($build['gpu']) && !empty($build['gpu']['name'])): ?>
                            <?php if (!empty($build['gpu']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['gpu']['image']); ?>" alt="GPU">
                                </div>
                            <?php endif; ?>
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
                        <?php if (isset($build['placaMae']) && is_array($build['placaMae']) && !empty($build['placaMae']['name'])): ?>
                            <?php if (!empty($build['placaMae']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['placaMae']['image']); ?>" alt="Placa-Mãe">
                                </div>
                            <?php endif; ?>
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
                        <?php if (isset($build['ram']) && is_array($build['ram']) && !empty($build['ram']['name'])): ?>
                            <?php if (!empty($build['ram']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['ram']['image']); ?>" alt="RAM">
                                </div>
                            <?php endif; ?>
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
                        <?php if (isset($build['armazenamento']) && is_array($build['armazenamento']) && !empty($build['armazenamento']['name'])): ?>
                            <?php if (!empty($build['armazenamento']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['armazenamento']['image']); ?>" alt="Armazenamento">
                                </div>
                            <?php endif; ?>
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
                        <?php if (isset($build['fonte']) && is_array($build['fonte']) && !empty($build['fonte']['name'])): ?>
                            <?php if (!empty($build['fonte']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['fonte']['image']); ?>" alt="Fonte">
                                </div>
                            <?php endif; ?>
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
                        <?php if (isset($build['gabinete']) && is_array($build['gabinete']) && !empty($build['gabinete']['name'])): ?>
                            <?php if (!empty($build['gabinete']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['gabinete']['image']); ?>" alt="Gabinete">
                                </div>
                            <?php endif; ?>
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
                        <?php if (isset($build['cooler']) && is_array($build['cooler']) && !empty($build['cooler']['name'])): ?>
                            <?php if (!empty($build['cooler']['image'])): ?>
                                <div class="selected-product-preview">
                                    <img src="<?php echo htmlspecialchars($build['cooler']['image']); ?>" alt="Cooler">
                                </div>
                            <?php endif; ?>
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
                        <input type="text" id="nomeSetup" placeholder="Ex: PC Gamer 2025" maxlength="100"
                            value="<?php echo htmlspecialchars($build['nomeSetup']); ?>">
                    </div>

                    <div class="summary-list" id="summaryList">
                        <?php
                        $hasComponents = false;
                        foreach ($componentLabels as $key => $label) {
                            if (isset($build[$key]) && is_array($build[$key]) && !empty($build[$key]['name'])) {
                                $hasComponents = true;
                                echo '<div class="summary-item">';
                                echo '<span class="summary-item-name">' . htmlspecialchars($label) . '</span>';
                                echo '<span class="summary-item-value">R$ ' . number_format($build[$key]['price'], 2, ',', '.') . '</span>';
                                echo '</div>';
                            }
                        }

                        if (!$hasComponents) {
                            echo '<p style="color: #94a3b8; text-align: center; padding: 20px; font-style: italic;">Nenhum componente selecionado ainda</p>';
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

                    <?php if (!$hasRequiredComponents): ?>
                        <div style="background: #fef3c7; border: 2px solid #fbbf24; border-radius: 10px; padding: 12px; margin-bottom: 16px; text-align: center;">
                            <p style="color: #92400e; font-size: 13px; margin: 0;">
                                <ion-icon name="warning-outline" style="vertical-align: middle; font-size: 16px;"></ion-icon>
                                Selecione todos os componentes obrigatórios para salvar
                            </p>
                        </div>
                    <?php endif; ?>

                    <button class="btn-save-build" id="saveBuildBtn" <?php echo !$hasRequiredComponents ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
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
                </div>
            </ul>
            <ul>
                <h3>AJUDA</h3>
                <div class="links">
                    <li><a href="../Fale Conosco/fale.php">Fale Conosco</a></li>
                    <li><a href="../Perfil/perfil.php">Sua Conta</a></li>
                </div>
            </ul>
            <ul>
                <h3>SERVIÇOS</h3>
                <div class="links">
                    <li><a href="../Catalogo/catalogo.php">Catálogo</a></li>
                    <li><a href="../Fale Conosco/fale.php">Suporte</a></li>
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

    <script>
        window.hasRequiredComponents = <?php echo $hasRequiredComponents ? 'true' : 'false'; ?>;
    </script>
    <script src="../Comum/common.js"></script>
    <script src="montarpc.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
<?php
require '../config.php';
require '../session.php';

$tipo = $_GET['tipo'] ?? '';

// Map component types to database types and session keys
$componentMap = [
    'processador' => ['db' => 'processador', 'session' => 'cpu', 'title' => 'Processador (CPU)', 'icon' => 'hardware-chip-outline'],
    'placa-video' => ['db' => 'placa de vídeo', 'session' => 'gpu', 'title' => 'Placa de Vídeo (GPU)', 'icon' => 'desktop-outline'],
    'placa-mae' => ['db' => 'placa-mãe', 'session' => 'placaMae', 'title' => 'Placa-Mãe', 'icon' => 'grid-outline'],
    'memoria' => ['db' => 'memória', 'session' => 'ram', 'title' => 'Memória RAM', 'icon' => 'albums-outline'],
    'armazenamento' => ['db' => 'armazenamento', 'session' => 'armazenamento', 'title' => 'Armazenamento', 'icon' => 'save-outline'],
    'fonte' => ['db' => 'fonte', 'session' => 'fonte', 'title' => 'Fonte de Alimentação', 'icon' => 'flash-outline'],
    'gabinete' => ['db' => 'gabinete', 'session' => 'gabinete', 'title' => 'Gabinete', 'icon' => 'cube-outline'],
    'cooler' => ['db' => 'cooler', 'session' => 'cooler', 'title' => 'Cooler', 'icon' => 'snow-outline']
];

if (!isset($componentMap[$tipo])) {
    header('Location: montarpc.php');
    exit();
}

$component = $componentMap[$tipo];

// Educational content for each component type
$educationalContent = [
    'processador' => [
        'intro' => 'O processador é o cérebro do seu computador, responsável por executar todas as instruções e cálculos.',
        'points' => [
            '<strong>Intel vs AMD:</strong> Intel oferece melhor desempenho single-core (ideal para jogos), enquanto AMD oferece mais núcleos pelo mesmo preço (melhor para multitarefa e produtividade).',
            '<strong>Núcleos e Threads:</strong> Mais núcleos = melhor multitarefa. Para jogos, 6-8 núcleos são suficientes. Para edição de vídeo e renderização, considere 12+ núcleos.',
            '<strong>Clock (GHz):</strong> Velocidade de processamento. Maior clock = melhor desempenho em tarefas single-core.',
            '<strong>Cache:</strong> Memória ultra-rápida do processador. Mais cache = melhor desempenho geral.',
            '<strong>TDP (Watts):</strong> Consumo de energia. Processadores com TDP maior precisam de coolers mais potentes.'
        ],
        'tips' => 'Para jogos: Ryzen 5 7600X ou Intel i5-13600K. Para produtividade: Ryzen 7 7700X ou Intel i7-13700K. Para workstation: Ryzen 9 7950X ou Intel i9-13900K.'
    ],
    'placa-video' => [
        'intro' => 'A placa de vídeo é responsável por processar gráficos e imagens, essencial para jogos e trabalhos visuais.',
        'points' => [
            '<strong>NVIDIA vs AMD:</strong> NVIDIA tem melhor ray tracing e DLSS (upscaling com IA). AMD oferece melhor custo-benefício e mais VRAM.',
            '<strong>VRAM:</strong> Memória da GPU. 8GB para 1080p, 12GB para 1440p, 16GB+ para 4K e trabalhos profissionais.',
            '<strong>Ray Tracing:</strong> Iluminação realista em jogos. Requer GPUs modernas (RTX 30/40 series ou RX 6000/7000).',
            '<strong>DLSS/FSR:</strong> Tecnologias de upscaling que melhoram performance mantendo qualidade visual.',
            '<strong>TDP:</strong> Consumo de energia. GPUs potentes precisam de fontes de 650W ou mais.'
        ],
        'tips' => '1080p: RTX 4060 ou RX 7600. 1440p: RTX 4070 ou RX 7800 XT. 4K: RTX 4080 ou RX 7900 XTX.'
    ],
    'placa-mae' => [
        'intro' => 'A placa-mãe conecta todos os componentes do PC. Escolha baseado no processador e recursos necessários.',
        'points' => [
            '<strong>Chipset:</strong> Determina recursos e compatibilidade. Intel: B760/Z790. AMD: B650/X670.',
            '<strong>Socket:</strong> Deve ser compatível com seu processador. Intel: LGA1700. AMD: AM5.',
            '<strong>Formato:</strong> ATX (padrão), Micro-ATX (menor), Mini-ITX (compacto).',
            '<strong>Slots PCIe:</strong> Para GPU, SSD NVMe e placas de expansão.',
            '<strong>Conectividade:</strong> Verifique portas USB, rede (2.5G ou 10G), WiFi e Bluetooth.'
        ],
        'tips' => 'Para jogos: B760/B650 é suficiente. Para overclock: Z790/X670. Verifique se tem slots M.2 suficientes para seus SSDs.'
    ],
    'memoria' => [
        'intro' => 'A memória RAM armazena dados temporários para acesso rápido pelo processador.',
        'points' => [
            '<strong>Capacidade:</strong> 16GB para jogos, 32GB para produtividade, 64GB+ para workstation.',
            '<strong>Velocidade (MHz):</strong> DDR4: 3200-3600MHz. DDR5: 5600-6000MHz. Mais rápido = melhor desempenho.',
            '<strong>Latência (CL):</strong> Menor é melhor. CL16 para DDR4, CL30-36 para DDR5.',
            '<strong>Dual Channel:</strong> Use 2 ou 4 pentes para melhor desempenho (nunca 1 ou 3).',
            '<strong>RGB:</strong> Puramente estético, não afeta desempenho.'
        ],
        'tips' => 'Para Intel 12ª-14ª gen e AMD Ryzen 7000: DDR5 5600MHz CL36. Para gerações anteriores: DDR4 3600MHz CL16.'
    ],
    'armazenamento' => [
        'intro' => 'Armazenamento guarda seus arquivos, programas e sistema operacional permanentemente.',
        'points' => [
            '<strong>SSD NVMe:</strong> Mais rápido (até 7000 MB/s). Use para sistema operacional e jogos principais.',
            '<strong>SSD SATA:</strong> Rápido (até 550 MB/s). Boa opção custo-benefício para armazenamento secundário.',
            '<strong>HD (HDD):</strong> Lento mas barato. Use apenas para arquivos grandes que não precisam de velocidade.',
            '<strong>Capacidade:</strong> 500GB mínimo para SO + jogos. 1TB recomendado. 2TB+ para biblioteca grande.',
            '<strong>PCIe Gen:</strong> Gen 3 (3500 MB/s), Gen 4 (7000 MB/s), Gen 5 (14000 MB/s). Gen 4 é o melhor custo-benefício.'
        ],
        'tips' => 'Configuração ideal: SSD NVMe 1TB (sistema + jogos) + SSD SATA 2TB ou HD 4TB (arquivos).'
    ],
    'fonte' => [
        'intro' => 'A fonte fornece energia estável e segura para todos os componentes do PC.',
        'points' => [
            '<strong>Potência (Watts):</strong> Calcule consumo total + 20-30% de margem. RTX 4070 = 650W. RTX 4090 = 1000W.',
            '<strong>Certificação 80 Plus:</strong> Bronze (82%), Gold (87%), Platinum (90%), Titanium (94%) de eficiência.',
            '<strong>Modular:</strong> Cabos removíveis para melhor organização. Semi-modular é bom custo-benefício.',
            '<strong>Proteções:</strong> OVP, UVP, OCP, OTP, SCP. Fontes de qualidade têm todas essas proteções.',
            '<strong>Garantia:</strong> Mínimo 5 anos. Fontes premium têm 10+ anos de garantia.'
        ],
        'tips' => 'Nunca economize na fonte. Uma fonte ruim pode danificar todos os componentes. Marcas confiáveis: Corsair, Seasonic, EVGA, XPG.'
    ],
    'gabinete' => [
        'intro' => 'O gabinete abriga todos os componentes e influencia diretamente no fluxo de ar e temperaturas.',
        'points' => [
            '<strong>Tamanho:</strong> Full Tower (grande), Mid Tower (padrão), Mini Tower (compacto). Mid Tower atende maioria dos builds.',
            '<strong>Fluxo de Ar:</strong> Frente: entrada de ar. Traseira/topo: saída. Mínimo 2 fans (1 entrada, 1 saída).',
            '<strong>Compatibilidade:</strong> Verifique se cabe sua GPU (comprimento) e cooler (altura).',
            '<strong>Painel Frontal:</strong> USB-C, USB 3.0, áudio. Verifique se sua placa-mãe tem os conectores.',
            '<strong>Gerenciamento de Cabos:</strong> Espaço atrás da placa-mãe para organizar cabos.'
        ],
        'tips' => 'Para melhor refrigeração: 3 fans frontais (entrada) + 1 traseiro + 1 topo (saída). Gabinetes com mesh frontal têm melhor fluxo de ar.'
    ],
    'cooler' => [
        'intro' => 'O cooler mantém o processador em temperaturas seguras. Processadores vêm com cooler básico, mas coolers aftermarket são melhores.',
        'points' => [
            '<strong>Air Cooler:</strong> Mais barato, confiável, sem manutenção. Suficiente para maioria dos processadores.',
            '<strong>Water Cooler (AIO):</strong> Melhor refrigeração, mais silencioso, visual premium. Requer manutenção eventual.',
            '<strong>TDP:</strong> Cooler deve suportar o TDP do processador. Ryzen 7/i7 = 150W+. Ryzen 9/i9 = 200W+.',
            '<strong>Altura:</strong> Verifique se cabe no gabinete. Air coolers grandes têm 160mm+.',
            '<strong>Radiador (AIO):</strong> 240mm para processadores até 8 núcleos. 280mm/360mm para 12+ núcleos.'
        ],
        'tips' => 'Air cooler: Deepcool AK400, Noctua NH-D15. Water cooler: Corsair H100i, NZXT Kraken. Para overclock pesado, prefira water cooler 280mm+.'
    ]
];

$content = $educationalContent[$tipo] ?? null;

// Fetch products from database
$dbType = $component['db'];
$query = "SELECT * FROM produtos WHERE LOWER(tipoProduto) LIKE LOWER(?) AND quantidadeProduto > 0 ORDER BY vendasProduto DESC";
$stmt = $conn->prepare($query);
$searchTerm = "%{$dbType}%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="selecionar-componente.css">
    <title><?php echo $component['title']; ?> - TechForge</title>
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
            <li><a href="../MontarPC/montarpc.php">MONTE SEU PC</a> <ion-icon class="navicon" name="desktop-outline"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="#">GAMER</a> <ion-icon class="navicon" name="game-controller-outline"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a> <ion-icon class="navicon" name="business-outline"></ion-icon></li>
        </ul>
    </nav>

    <div class="container-component">
        <div class="back-button">
            <a href="montarpc.php">
                <ion-icon name="arrow-back-outline"></ion-icon>
                Voltar para Montagem
            </a>
        </div>

        <div class="component-header">
            <div class="component-icon-large">
                <ion-icon name="<?php echo $component['icon']; ?>"></ion-icon>
            </div>
            <h1><?php echo $component['title']; ?></h1>
            <p>Aprenda a escolher o melhor componente para seu PC</p>
        </div>

        <?php if ($content): ?>
        <div class="education-section">
            <div class="education-card">
                <h2>Como Escolher</h2>
                <p class="intro"><?php echo $content['intro']; ?></p>
                
                <h3>Pontos Importantes:</h3>
                <ul class="points-list">
                    <?php foreach ($content['points'] as $point): ?>
                        <li><?php echo $point; ?></li>
                    <?php endforeach; ?>
                </ul>

                <div class="tips-box">
                    <h4><ion-icon name="bulb-outline"></ion-icon> Dica de Especialista</h4>
                    <p><?php echo $content['tips']; ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="products-section">
            <h2>Escolha Seu <?php echo $component['title']; ?></h2>
            <p class="products-subtitle">Clique em um produto para adicioná-lo à sua montagem</p>

            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card" data-product-id="<?php echo $product['idProduto']; ?>" 
                     data-product-name="<?php echo htmlspecialchars($product['nomeProduto']); ?>"
                     data-product-price="<?php echo $product['valorProduto']; ?>">
                    <div class="product-image">
                        <?php if (!empty($product['imagem'])): ?>
                            <img src="<?php echo htmlspecialchars($product['imagem']); ?>" alt="<?php echo htmlspecialchars($product['nomeProduto']); ?>">
                        <?php else: ?>
                            <img src="/placeholder.svg?height=200&width=200" alt="Produto sem imagem">
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['nomeProduto']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars(substr($product['descricaoProduto'], 0, 100)) . '...'; ?></p>
                        <div class="product-price">R$ <?php echo number_format($product['valorProduto'], 2, ',', '.'); ?></div>
                        <button class="btn-select-product">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                            Selecionar
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($products)): ?>
                <div class="no-products">
                    <ion-icon name="sad-outline"></ion-icon>
                    <p>Nenhum produto disponível no momento</p>
                    <a href="montarpc.php" class="btn-back">Voltar para Montagem</a>
                </div>
            <?php endif; ?>
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

    <script src="../Comum/common.js"></script>
    <script>
        const componentType = '<?php echo $tipo; ?>';
        const sessionKey = '<?php echo $component['session']; ?>';
    </script>
    <script src="selecionar-componente.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

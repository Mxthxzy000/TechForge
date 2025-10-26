<?php
require '../config.php';
require '../session.php';
require '../flash.php';

if (empty($_SESSION['idUsuario'])) {
    header('Location: ../Login/login.php');
    exit;
}

$idPedido = $_GET['id'] ?? 0;

// Get order details
$stmt = $conn->prepare("
    SELECT p.*, e.rua, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.cep
    FROM pedido p
    LEFT JOIN endereco e ON p.idEndereco = e.idEndereco
    WHERE p.idPedido = ? AND p.idUsuario = ?
");
$stmt->bind_param("ii", $idPedido, $_SESSION['idUsuario']);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    header('Location: perfil.php');
    exit;
}

// Get order items
$stmt = $conn->prepare("
    SELECT ip.*, pr.nomeProduto, pr.imagem
    FROM item_pedido ip
    JOIN produtos pr ON ip.idProduto = pr.idProduto
    WHERE ip.idPedido = ?
");
$stmt->bind_param("i", $idPedido);
$stmt->execute();
$itens = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="perfil.css">
    <title>Detalhes do Pedido #<?php echo $idPedido; ?> - TechForge</title>
    <style>
        .order-details-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .order-header-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .info-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .items-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .item-row {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .item-row:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .item-info {
            flex: 1;
        }
        
        .item-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #64748b;
        }
        
        .order-total {
            text-align: right;
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-pendente {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-pago {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-enviado {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-concluido {
            background: #d1fae5;
            color: #065f46;
        }
    </style>
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
            <li><a href="../Catalogo/catalogo.php">PRODUTOS</a> <ion-icon class="navicon" name="bag-outline"></ion-icon></li>
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

    <div class="order-details-container">
        <div class="order-header-section">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Pedido #<?php echo $idPedido; ?></h1>
                <span class="status-badge status-<?php echo $pedido['status']; ?>">
                    <?php 
                    $statusLabels = [
                        'pendente' => 'Pendente',
                        'pago' => 'Pago',
                        'enviado' => 'Enviado',
                        'concluido' => 'Concluído'
                    ];
                    echo $statusLabels[$pedido['status']] ?? $pedido['status'];
                    ?>
                </span>
            </div>
            
            <div class="order-info-grid">
                <div class="info-item">
                    <div class="info-label">Data do Pedido</div>
                    <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($pedido['dataPedido'])); ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Método de Pagamento</div>
                    <div class="info-value"><?php echo ucfirst($pedido['metodoPagamento']); ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Endereço de Entrega</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($pedido['rua'] . ', ' . $pedido['numero']); ?><br>
                        <small style="font-weight: 400; color: #64748b;">
                            <?php echo htmlspecialchars($pedido['bairro'] . ' - ' . $pedido['cidade'] . '/' . $pedido['estado']); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="items-section">
            <h2 style="margin-bottom: 20px;">Itens do Pedido</h2>
            
            <?php foreach ($itens as $item): ?>
                <div class="item-row">
                    <img src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nomeProduto']); ?>" class="item-image">
                    <div class="item-info">
                        <div class="item-name"><?php echo htmlspecialchars($item['nomeProduto']); ?></div>
                        <div class="item-price">Quantidade: <?php echo $item['quantidade']; ?> × R$ <?php echo number_format($item['precoUnitario'], 2, ',', '.'); ?></div>
                    </div>
                    <div style="font-weight: 600; font-size: 18px;">
                        R$ <?php echo number_format($item['quantidade'] * $item['precoUnitario'], 2, ',', '.'); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="order-total">
                Total: R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?>
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="perfil.php" class="btn btn-secondary" style="display: inline-block; padding: 12px 30px; text-decoration: none;">
                Voltar para Meus Pedidos
            </a>
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

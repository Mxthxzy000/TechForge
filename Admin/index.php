<?php
require '../config.php';
require 'session-check.php';
require '../flash.php';

// Buscar estatísticas
$stats = [
    'produtos' => 0,
    'usuarios' => 0,
    'pedidos' => 0,
    'montagens' => 0,
    'contatos' => 0
];

$stats['produtos'] = $conn->query("SELECT COUNT(*) as total FROM produtos")->fetch_assoc()['total'];
$stats['usuarios'] = $conn->query("SELECT COUNT(*) as total FROM usuario")->fetch_assoc()['total'];
$stats['pedidos'] = $conn->query("SELECT COUNT(*) as total FROM pedido")->fetch_assoc()['total'];
$stats['montagens'] = $conn->query("SELECT COUNT(*) as total FROM servico_montagem")->fetch_assoc()['total'];
$stats['contatos'] = $conn->query("SELECT COUNT(*) as total FROM contatos")->fetch_assoc()['total'];

// Pedidos recentes
$pedidosRecentes = $conn->query("
    SELECT p.*, u.nomeUsuario 
    FROM pedido p 
    LEFT JOIN usuario u ON p.idUsuario = u.idUsuario 
    ORDER BY p.dataPedido DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Dashboard Admin - TechForge</title>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../imagens/logo_header_TechForge.png" alt="TechForge" class="sidebar-logo">
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item active">
                    <ion-icon name="grid-outline"></ion-icon>
                    Dashboard
                </a>
                <a href="produtos.php" class="nav-item">
                    <ion-icon name="cube-outline"></ion-icon>
                    Produtos
                </a>
                <a href="pedidos.php" class="nav-item">
                    <ion-icon name="receipt-outline"></ion-icon>
                    Pedidos
                </a>
                <a href="usuarios.php" class="nav-item">
                    <ion-icon name="people-outline"></ion-icon>
                    Usuários
                </a>
                <a href="montagens.php" class="nav-item">
                    <ion-icon name="desktop-outline"></ion-icon>
                    Montagens PC
                </a>
                <a href="contatos.php" class="nav-item">
                    <ion-icon name="mail-outline"></ion-icon>
                    Contatos
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-info">
                    <ion-icon name="person-circle-outline"></ion-icon>
                    <!-- Corrigido de nomeAdmin para nomeAdm -->
                    <span><?php echo htmlspecialchars($_SESSION['nomeAdm']); ?></span>
                </div>
                <a href="../logout.php" class="btn-logout">
                    <ion-icon name="log-out-outline"></ion-icon>
                    Sair
                </a>
            </div>
        </aside>

        <main class="admin-content">
            <header class="content-header">
                <h1>Dashboard</h1>
                <div class="header-actions">
                    <a href="../Home/index.php" class="btn-secondary" target="_blank">
                        <ion-icon name="globe-outline"></ion-icon>
                        Ver Site
                    </a>
                </div>
            </header>

            <?php show_flash(); ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon products">
                        <ion-icon name="cube-outline"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['produtos']; ?></h3>
                        <p>Produtos</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon users">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['usuarios']; ?></h3>
                        <p>Usuários</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orders">
                        <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pedidos']; ?></h3>
                        <p>Pedidos</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon builds">
                        <ion-icon name="desktop-outline"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['montagens']; ?></h3>
                        <p>Montagens PC</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon messages">
                        <ion-icon name="mail-outline"></ion-icon>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['contatos']; ?></h3>
                        <p>Mensagens</p>
                    </div>
                </div>
            </div>

            <div class="recent-section">
                <h2>Pedidos Recentes</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($pedidosRecentes && $pedidosRecentes->num_rows > 0): ?>
                                <?php while ($pedido = $pedidosRecentes->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo $pedido['idPedido']; ?></td>
                                        <td><?php echo htmlspecialchars($pedido['nomeUsuario'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($pedido['dataPedido'])); ?></td>
                                        <td>R$ <?php echo number_format($pedido['valorTotal'], 2, ',', '.'); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo strtolower($pedido['statusPedido']); ?>">
                                                <?php echo htmlspecialchars($pedido['statusPedido']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="pedidos.php?id=<?php echo $pedido['idPedido']; ?>" class="btn-icon">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum pedido encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="admin.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>

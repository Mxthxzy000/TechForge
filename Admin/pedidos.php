<?php
require '../config.php';
require 'session-check.php';
require '../flash.php';

// Buscar todos os pedidos com informações do usuário
$pedidos = $conn->query("
    SELECT p.*, u.nomeUsuario, u.emailUsuario 
    FROM pedido p 
    LEFT JOIN usuario u ON p.idUsuario = u.idUsuario 
    ORDER BY p.dataPedido DESC
");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Gerenciar Pedidos - TechForge</title>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../imagens/logo_header_TechForge.png" alt="TechForge" class="sidebar-logo">
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item">
                    <ion-icon name="grid-outline"></ion-icon>
                    Dashboard
                </a>
                <a href="produtos.php" class="nav-item">
                    <ion-icon name="cube-outline"></ion-icon>
                    Produtos
                </a>
                <a href="pedidos.php" class="nav-item active">
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
                <h1>Gerenciar Pedidos</h1>
            </header>

            <?php show_flash(); ?>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Total</th>
                            <th>Pagamento</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pedidos && $pedidos->num_rows > 0): ?>
                            <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $pedido['idPedido']; ?></td>
                                    <td>
                                        <div class="user-info">
                                            <strong><?php echo htmlspecialchars($pedido['nomeUsuario'] ?? 'N/A'); ?></strong>
                                            <small><?php echo htmlspecialchars($pedido['emailUsuario'] ?? ''); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['dataPedido'])); ?></td>
                                    <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['metodoPagamento']); ?></td>
                                    <td>
                                        <select class="status-select" onchange="updateOrderStatus(<?php echo $pedido['idPedido']; ?>, this.value)">
                                            <option value="pendente" <?php echo $pedido['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                            <option value="pago" <?php echo $pedido['status'] === 'pago' ? 'selected' : ''; ?>>Pago</option>
                                            <option value="enviado" <?php echo $pedido['status'] === 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                            <option value="concluido" <?php echo $pedido['status'] === 'concluido' ? 'selected' : ''; ?>>Concluído</option>
                                            <option value="cancelado" <?php echo $pedido['status'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button onclick="viewOrderDetails(<?php echo $pedido['idPedido']; ?>)" class="btn-icon" title="Ver Detalhes">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum pedido encontrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal para detalhes do pedido -->
    <div id="orderModal" class="modal">
        <div class="modal-content large">
            <div class="modal-header">
                <h2>Detalhes do Pedido</h2>
                <button onclick="closeOrderModal()" class="btn-close">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div id="orderDetails" class="modal-body">
                <!-- Conteúdo carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>

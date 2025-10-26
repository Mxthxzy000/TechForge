<?php
require '../config.php';
require 'session-check.php';
require '../flash.php';

// Buscar todas as solicitações de montagem
$montagens = $conn->query("
    SELECT s.*, u.nomeUsuario, u.emailUsuario 
    FROM servico_montagem s 
    LEFT JOIN usuario u ON s.idUsuario = u.idUsuario 
    ORDER BY s.dataSolicitacao DESC
");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Solicitações de Montagem - TechForge</title>
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
                <a href="pedidos.php" class="nav-item">
                    <ion-icon name="receipt-outline"></ion-icon>
                    Pedidos
                </a>
                <a href="usuarios.php" class="nav-item">
                    <ion-icon name="people-outline"></ion-icon>
                    Usuários
                </a>
                <a href="montagens.php" class="nav-item active">
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
                <h1>Solicitações de Montagem de PC</h1>
            </header>

            <?php show_flash(); ?>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Nome do Build</th>
                            <th>Data Solicitação</th>
                            <th>Valor Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($montagens && $montagens->num_rows > 0): ?>
                            <?php while ($montagem = $montagens->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $montagem['idMontagem']; ?></td>
                                    <td>
                                        <div class="user-info">
                                            <strong><?php echo htmlspecialchars($montagem['nomeUsuario'] ?? 'N/A'); ?></strong>
                                            <small><?php echo htmlspecialchars($montagem['emailUsuario'] ?? ''); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($montagem['nomeSetup']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($montagem['dataSolicitacao'])); ?></td>
                                    <td>R$ <?php echo number_format($montagem['precoEstimado'], 2, ',', '.'); ?></td>
                                    <td>
                                        <button onclick="viewBuildDetails(<?php echo $montagem['idMontagem']; ?>)" class="btn-icon" title="Ver Detalhes">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button onclick="deleteBuild(<?php echo $montagem['idMontagem']; ?>)" class="btn-icon danger" title="Excluir">
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Nenhuma solicitação de montagem encontrada</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal para detalhes do build -->
    <div id="buildModal" class="modal">
        <div class="modal-content large">
            <div class="modal-header">
                <h2>Detalhes do Build</h2>
                <button onclick="closeBuildModal()" class="btn-close">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div id="buildDetails" class="modal-body">
                <!-- Conteúdo carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>

<?php
require '../config.php';
require 'session-check.php';
require '../flash.php';

$contatos = $conn->query("SELECT * FROM contatos ORDER BY data_envio DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Mensagens de Contato - TechForge</title>
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
                <a href="montagens.php" class="nav-item">
                    <ion-icon name="desktop-outline"></ion-icon>
                    Montagens PC
                </a>
                <a href="contatos.php" class="nav-item active">
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
                <h1>Mensagens de Contato</h1>
            </header>

            <?php show_flash(); ?>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Assunto</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($contatos && $contatos->num_rows > 0): ?>
                            <?php while ($contato = $contatos->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $contato['id']; ?></td>
                                    <td><?php echo htmlspecialchars($contato['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($contato['email']); ?></td>
                                    <td><?php echo htmlspecialchars($contato['assunto']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($contato['data_envio'])); ?></td>
                                    <td>
                                        <button onclick='viewMessage(<?php echo json_encode($contato); ?>)' class="btn-icon" title="Ver Mensagem">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button onclick="deleteMessage(<?php echo $contato['id']; ?>)" class="btn-icon danger" title="Excluir">
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Nenhuma mensagem encontrada</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal para visualizar mensagem -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Mensagem de Contato</h2>
                <button onclick="closeMessageModal()" class="btn-close">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div id="messageContent" class="modal-body">
                <!-- Conteúdo carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>

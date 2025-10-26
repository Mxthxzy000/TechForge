<?php
require '../config.php';
require 'session-check.php';
require '../flash.php';

// Buscar todos os usuários
$usuarios = $conn->query("SELECT * FROM usuario ORDER BY idUsuario DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Gerenciar Usuários - TechForge</title>
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
                <a href="usuarios.php" class="nav-item active">
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
                <h1>Gerenciar Usuários</h1>
                <!-- Added button to create new user -->
                <div class="header-actions">
                    <button onclick="openUserModal()" class="btn-primary">
                        <ion-icon name="add-outline"></ion-icon>
                        Novo Usuário
                    </button>
                </div>
            </header>

            <?php show_flash(); ?>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($usuarios && $usuarios->num_rows > 0): ?>
                            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $usuario['idUsuario']; ?></td>
                                    <td>
                                        <?php if (!empty($usuario['fotoUsuario'])): ?>
                                            <img src="<?php echo htmlspecialchars($usuario['fotoUsuario']); ?>" 
                                                 alt="Foto" class="user-avatar">
                                        <?php else: ?>
                                            <div class="user-avatar-placeholder">
                                                <ion-icon name="person-outline"></ion-icon>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['nomeUsuario'] . ' ' . $usuario['sobrenomeUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['emailUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['cpfUsuario'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['celularUsuario']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($usuario['dataCadastro'])); ?></td>
                                    <td>
                                        <button onclick="viewUserDetails(<?php echo $usuario['idUsuario']; ?>)" class="btn-icon" title="Ver Detalhes">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                        <button onclick="deleteUser(<?php echo $usuario['idUsuario']; ?>)" class="btn-icon danger" title="Excluir">
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Nenhum usuário cadastrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Added modal for creating new user -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="userModalTitle">Novo Usuário</h2>
                <button onclick="closeUserModal()" class="btn-close">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <form id="userForm" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nomeUsuario">Nome *</label>
                        <input type="text" id="nomeUsuario" name="nomeUsuario" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sobrenomeUsuario">Sobrenome *</label>
                        <input type="text" id="sobrenomeUsuario" name="sobrenomeUsuario" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="emailUsuario">E-mail *</label>
                    <input type="email" id="emailUsuario" name="emailUsuario" required>
                </div>

                <div class="form-group">
                    <label for="senhaUsuario">Senha *</label>
                    <input type="password" id="senhaUsuario" name="senhaUsuario" required minlength="6">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cpfUsuario">CPF</label>
                        <input type="text" id="cpfUsuario" name="cpfUsuario" maxlength="14" placeholder="000.000.000-00">
                    </div>
                    
                    <div class="form-group">
                        <label for="celularUsuario">Celular</label>
                        <input type="text" id="celularUsuario" name="celularUsuario" maxlength="15" placeholder="(00) 00000-0000">
                    </div>
                </div>

                <div class="form-group">
                    <label for="nascimentoUsuario">Data de Nascimento</label>
                    <input type="date" id="nascimentoUsuario" name="nascimentoUsuario">
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeUserModal()" class="btn-secondary">Cancelar</button>
                    <button type="submit" class="btn-primary">Criar Usuário</button>
                </div>
            </form>
        </div>
    </div>

    <script src="admin.js"></script>
    <script>
        function openUserModal() {
            document.getElementById('userModal').classList.add('active');
            document.getElementById('userForm').reset();
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.remove('active');
        }

        // Handle user form submission
        document.getElementById('userForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            formData.append('action', 'addUser');
            
            try {
                const response = await fetch('adminAPI.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire('Sucesso!', result.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erro!', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Erro!', 'Erro ao criar usuário', 'error');
            }
        });

        // Format CPF input
        document.getElementById('cpfUsuario').addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });

        // Format phone input
        document.getElementById('celularUsuario').addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>

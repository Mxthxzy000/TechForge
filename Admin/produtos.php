<?php
require '../config.php';
require 'session-check.php';
require '../flash.php';

// Buscar todos os produtos
$produtos = $conn->query("SELECT * FROM produtos ORDER BY idProduto DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Gerenciar Produtos - TechForge</title>
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
                <a href="produtos.php" class="nav-item active">
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
                <h1>Gerenciar Produtos</h1>
                <div class="header-actions">
                    <button onclick="openProductModal()" class="btn-primary">
                        <ion-icon name="add-outline"></ion-icon>
                        Novo Produto
                    </button>
                </div>
            </header>

            <?php show_flash(); ?>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Categoria</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="productsTable">
                        <?php if ($produtos && $produtos->num_rows > 0): ?>
                            <?php while ($produto = $produtos->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $produto['idProduto']; ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                             alt="<?php echo htmlspecialchars($produto['nomeProduto']); ?>" 
                                             class="product-thumb">
                                    </td>
                                    <td><?php echo htmlspecialchars($produto['nomeProduto']); ?></td>
                                    <td>R$ <?php echo number_format($produto['valorProduto'], 2, ',', '.'); ?></td>
                                    <td><?php echo $produto['quantidadeProduto']; ?></td>
                                    <td><?php echo htmlspecialchars($produto['tipoProduto']); ?></td>
                                    <td>
                                        <button onclick='editProduct(<?php echo json_encode($produto); ?>)' class="btn-icon" title="Editar">
                                            <ion-icon name="create-outline"></ion-icon>
                                        </button>
                                        <button onclick="deleteProduct(<?php echo $produto['idProduto']; ?>)" class="btn-icon danger" title="Excluir">
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum produto cadastrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal para adicionar/editar produto -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Novo Produto</h2>
                <button onclick="closeProductModal()" class="btn-close">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <form id="productForm" class="modal-form">
                <input type="hidden" id="productId" name="idProduto">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nomeProduto">Nome do Produto *</label>
                        <input type="text" id="nomeProduto" name="nomeProduto" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="precoProduto">Preço (R$) *</label>
                        <input type="number" id="precoProduto" name="precoProduto" step="0.01" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="estoqueProduto">Estoque *</label>
                        <input type="number" id="estoqueProduto" name="estoqueProduto" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="categoriaProduto">Categoria *</label>
                        <input type="text" id="categoriaProduto" name="categoriaProduto" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricaoProduto">Descrição</label>
                    <textarea id="descricaoProduto" name="descricaoProduto" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="tagsProduto">Tags (separadas por vírgula)</label>
                    <input type="text" id="tagsProduto" name="tagsProduto" placeholder="Ex: Intel, Gaming, RGB">
                </div>

                <div class="form-group">
                    <label for="linhaProduto">Linha do Produto</label>
                    <select id="linhaProduto" name="linhaProduto" class="form-input">
                        <option value="">Selecione...</option>
                        <option value="Intel">Intel</option>
                        <option value="AMD">AMD</option>
                        <option value="NVIDIA">NVIDIA</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Imagem do Produto *</label>
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <button type="button" onclick="toggleImageInput('url')" class="btn-secondary" id="btnUrlInput">URL</button>
                        <button type="button" onclick="toggleImageInput('file')" class="btn-secondary" id="btnFileInput">Upload</button>
                    </div>
                    
                    <div id="urlImageInput">
                        <input type="text" id="imagemProdutoUrl" name="imagemProdutoUrl" placeholder="Cole a URL da imagem">
                    </div>
                    
                    <div id="fileImageInput" style="display: none;">
                        <input type="file" id="imagemProdutoFile" name="imagemProdutoFile" accept="image/*">
                        <small style="color: #666; display: block; margin-top: 5px;">A imagem será salva em ../imagens_produtos/</small>
                    </div>
                    
                    <input type="hidden" id="imagemProduto" name="imagemProduto">
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeProductModal()" class="btn-secondary">Cancelar</button>
                    <button type="submit" class="btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>

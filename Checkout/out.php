
<?php
require '../config.php';
require '../session.php';

// Verificar se usuário está logado
if (empty($_SESSION['idUsuario'])) {
    header('Location: ../login/login.php');
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

// Obter endereços salvos do usuário
$queryEnderecos = "SELECT * FROM endereco WHERE idUsuario = ? ORDER BY tipoEndereco DESC, idEndereco ASC";
$stmt = $conn->prepare($queryEnderecos);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultEnderecos = $stmt->get_result();
$enderecos = [];
while ($row = $resultEnderecos->fetch_assoc()) {
    $enderecos[] = $row;
}

// Obter métodos de pagamento salvos
$queryPagamentos = "SELECT * FROM formas_pagamento WHERE idUsuario = ? ORDER BY dataCadastro DESC";
$stmt = $conn->prepare($queryPagamentos);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultPagamentos = $stmt->get_result();
$pagamentos = [];
while ($row = $resultPagamentos->fetch_assoc()) {
    // Ocultar números de cartão por segurança
    if (!empty($row['numeroCartao'])) {
        $row['numeroCartao'] = '**** **** **** ' . substr($row['numeroCartao'], -4);
    }
    $pagamentos[] = $row;
}

// Obter total do carrinho
$queryCarrinho = "SELECT SUM(ic.precoUnitario * ic.quantidade) as total 
                 FROM carrinho c 
                 JOIN item_carrinho ic ON c.idCarrinho = ic.idCarrinho 
                 WHERE c.idUsuario = ? AND c.status = 'ativo'";
$stmt = $conn->prepare($queryCarrinho);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultCarrinho = $stmt->get_result()->fetch_assoc();
$totalCarrinho = isset($resultCarrinho['total']) ? $resultCarrinho['total'] : 0;

// Obter dados de montagem de PC se houver na sessão
$montagemId = $_GET['montagem_id'] ?? null;
$pedidoMontagem = null;
if ($montagemId) {
    $queryMontagem = "SELECT * FROM servico_montagem WHERE idMontagem = ? AND idUsuario = ?";
    $stmt = $conn->prepare($queryMontagem);
    $stmt->bind_param('ii', $montagemId, $idUsuario);
    $stmt->execute();
    $resultMontagem = $stmt->get_result();
    if ($row = $resultMontagem->fetch_assoc()) {
        $pedidoMontagem = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechForge</title>
    <link rel="stylesheet" href="../Comum/common.css">
    <link rel="stylesheet" href="out.css">
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

    <nav>
        <ul>
            <li><a href="../Catalogo/catalogo.php">PRODUTOS</a> <ion-icon name="bag-outline" class="navicon"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="../MontarPC/montarpc.php">MONTE SEU PC</a> <ion-icon class="navicon" name="desktop-outline"></ion-icon></li>
            <span class="linha"></span>
            <li><a href="../Sobre/sobre.php">SOBRE NÓS</a> <ion-icon class="navicon" name="business-outline"></ion-icon></li>
        </ul>
    </nav>

    <div class="checkout-wrapper">
        <div class="checkout-container">
            <!-- Resumo do Pedido (Sidebar) -->
            <aside class="checkout-sidebar">
                <h2>Resumo do Pedido</h2>
                
                <?php if ($pedidoMontagem): ?>
                    <div class="summary-section">
                        <h3>Serviço de Montagem</h3>
                        <div class="summary-item">
                            <span><?php echo htmlspecialchars($pedidoMontagem['nomeSetup']); ?></span>
                            <span id="subtotal-montagem">R$ <?php echo number_format($pedidoMontagem['precoEstimado'], 2, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="summary-section">
                        <h3>Produtos</h3>
                        <div id="resumo-produtos"></div>
                    </div>
                <?php endif; ?>

                <div class="summary-section">
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <span id="subtotal">R$ 0,00</span>
                    </div>
                    <div class="summary-item">
                        <span>Frete:</span>
                        <span id="frete">R$ 0,00</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-item total">
                        <span>Total:</span>
                        <span id="total">R$ 0,00</span>
                    </div>
                </div>
            </aside>

            <!-- Conteúdo Principal -->
            <main class="checkout-main">
                <h1>Finalizar Compra</h1>

                <!-- Seção de Endereço -->
                <section class="checkout-section">
                    <h2>Endereço de Entrega</h2>
                    
                    <?php if (!empty($enderecos)): ?>
                        <div class="endereco-salvo">
                            <h3>Meus Endereços</h3>
                            <div class="list-enderecos" id="listEnderecos">
                                <?php foreach ($enderecos as $endereco): ?>
                                    <label class="endereco-card">
                                        <input type="radio" name="endereco" value="<?php echo $endereco['idEndereco']; ?>" 
                                               data-estado="<?php echo $endereco['estado']; ?>"
                                               class="endereco-radio">
                                        <div class="endereco-info">
                                            <strong><?php echo htmlspecialchars($endereco['rua']); ?>, <?php echo htmlspecialchars($endereco['numero']); ?></strong>
                                            <?php if ($endereco['complemento']): ?>
                                                <p><?php echo htmlspecialchars($endereco['complemento']); ?></p>
                                            <?php endif; ?>
                                            <p><?php echo htmlspecialchars($endereco['bairro']); ?>, <?php echo htmlspecialchars($endereco['cidade']); ?> - <?php echo htmlspecialchars($endereco['estado']); ?></p>
                                            <p class="cep">CEP: <?php echo htmlspecialchars($endereco['cep']); ?></p>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="empty-message">Nenhum endereço salvo. Adicione um novo.</p>
                    <?php endif; ?>

                    <!-- Buscar novo endereço por CEP -->
                    <div class="buscar-cep">
                        <h3>Buscar Endereço por CEP</h3>
                        <div class="cep-input-group">
                            <input type="text" id="cepInput" placeholder="Digite seu CEP (ex: 12289-160)" class="cep-input">
                            <button id="searchCepBtn" class="btn btn-primary">Buscar</button>
                        </div>
                        
                        <div id="resultadoEndereco" class="resultado-endereco" style="display: none;">
                            <div class="form-group">
                                <label>Rua</label>
                                <input type="text" id="street" class="form-input" readonly>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Número</label>
                                    <input type="text" id="number" class="form-input" placeholder="123">
                                </div>
                                <div class="form-group">
                                    <label>Complemento</label>
                                    <input type="text" id="complement" class="form-input" placeholder="Apto, sala, etc">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Bairro</label>
                                    <input type="text" id="neighborhood" class="form-input" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" id="city" class="form-input" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" id="state" class="form-input" readonly>
                            </div>
                            <button type="button" id="usarNovoEndereco" class="btn btn-secondary">Usar Este Endereço</button>
                        </div>
                    </div>
                </section>

                <!-- Seção de Frete -->
                <section class="checkout-section">
                    <h2>Opções de Frete</h2>
                    <div id="freteOptions" class="frete-options">
                        <p class="loading-message">Selecione um endereço para ver opções de frete</p>
                    </div>
                </section>

                <!-- Seção de Pagamento -->
                <section class="checkout-section">
                    <h2>Método de Pagamento</h2>
                    
                    <?php if (!empty($pagamentos)): ?>
                        <div class="pagamento-salvo">
                            <h3>Meus Métodos</h3>
                            <div class="list-pagamentos" id="listPagamentos">
                                <?php foreach ($pagamentos as $pagamento): ?>
                                    <label class="pagamento-card">
                                        <input type="radio" name="pagamento" value="<?php echo $pagamento['idFormaPagamento']; ?>" 
                                               data-tipo="<?php echo htmlspecialchars($pagamento['tipoPagamento']); ?>"
                                               class="pagamento-radio">
                                        <div class="pagamento-info">
                                            <strong><?php echo ucfirst(str_replace('_', ' ', $pagamento['tipoPagamento'])); ?></strong>
                                            <p>
                                                <?php 
                                                    if ($pagamento['tipoPagamento'] === 'cartao_credito') {
                                                        echo 'Cartão: ' . htmlspecialchars($pagamento['numeroCartao']);
                                                        if ($pagamento['nomeTitular']) echo ' - ' . htmlspecialchars($pagamento['nomeTitular']);
                                                    } elseif ($pagamento['tipoPagamento'] === 'pix') {
                                                        echo 'Chave PIX: ' . substr(htmlspecialchars($pagamento['chavePix']), 0, 20) . '...';
                                                    } else {
                                                        echo htmlspecialchars($pagamento['nomeTitular'] ?? 'Sem nome');
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="empty-message">Nenhum método de pagamento salvo. Adicione um novo.</p>
                    <?php endif; ?>

                    <!-- Adicionar novo método -->
                    <div class="adicionar-pagamento">
                        <h3>Adicionar Método de Pagamento</h3>
                        <div class="payment-method-selector">
                            <label class="method-option">
                                <input type="radio" name="novo_pagamento_tipo" value="cartao_credito">
                                <span>Cartão de Crédito</span>
                            </label>
                            <label class="method-option">
                                <input type="radio" name="novo_pagamento_tipo" value="pix">
                                <span>PIX</span>
                            </label>
                            <label class="method-option">
                                <input type="radio" name="novo_pagamento_tipo" value="boleto">
                                <span>Boleto</span>
                            </label>
                        </div>

                        <!-- Formulário Cartão -->
                        <div id="cartaoForm" class="payment-form" style="display: none;">
                            <div class="form-group">
                                <label>Nome do Titular</label>
                                <input type="text" id="nomeCartao" class="form-input" placeholder="Nome como aparece no cartão">
                            </div>
                            <div class="form-group">
                                <label>Número do Cartão</label>
                                <input type="text" id="numeroCartao" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Validade (MM/AA)</label>
                                    <input type="text" id="validadeCartao" class="form-input" placeholder="12/28" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label>CVV</label>
                                    <input type="text" id="cvvCartao" class="form-input" placeholder="123" maxlength="4">
                                </div>
                            </div>
                            <label class="checkbox-save">
                                <input type="checkbox" id="salvarCartao" checked>
                                <span>Salvar este cartão para próximas compras</span>
                            </label>
                        </div>

                        <!-- Formulário PIX -->
                        <div id="pixForm" class="payment-form" style="display: none;">
                            <div class="pix-info">
                                <p>Use PIX para pagamento instantâneo. Você receberá um código QR para escanear.</p>
                            </div>
                            <div class="form-group">
                                <label>Chave PIX</label>
                                <input type="text" id="chavePix" class="form-input" placeholder="Email, CPF, telefone ou chave aleatória">
                            </div>
                            <label class="checkbox-save">
                                <input type="checkbox" id="salvarPix" checked>
                                <span>Salvar este PIX para próximas compras</span>
                            </label>
                        </div>

                        <!-- Formulário Boleto -->
                        <div id="boletoForm" class="payment-form" style="display: none;">
                            <div class="boleto-info">
                                <p>O boleto será enviado para seu email após confirmar a compra.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Botão Finalizar -->
                <section class="checkout-actions">
                    <button id="finalizarBtn" class="btn btn-success">Finalizar Compra</button>
                    <a href="<?php echo $pedidoMontagem ? '../MontarPC/montarpc.php' : '../Carrinho/carrinho.php'; ?>" class="btn btn-cancel">Cancelar</a>
                </section>
            </main>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Confirmar Pedido</h2>
            <div id="modalResumo"></div>
            <div class="modal-actions">
                <button id="confirmarBtn" class="btn btn-success">Confirmar</button>
                <button id="cancelarBtn" class="btn btn-cancel">Cancelar</button>
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
    <script src="out.js"></script>
    <!-- added SweetAlert for success messages -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- incluir script de notificação centralizado -->
    <script src="../js/notification.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
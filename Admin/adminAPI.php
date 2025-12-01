<?php
require '../config.php';
require 'session-check.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        // PRODUTOS
        case 'addProduct':
            // Validação dos campos obrigatórios
            if (empty($_POST['nomeProduto']) || empty($_POST['valorProduto']) || empty($_POST['quantidadeProduto']) || empty($_POST['tipoProduto'])) {
                echo json_encode(['success' => false, 'message' => 'Campos obrigatórios não preenchidos']);
                break;
            }
            
            $linhaProduto = !empty($_POST['linhaProduto']) ? $_POST['linhaProduto'] : 'Genérico';
            $descricao = !empty($_POST['descricaoProduto']) ? $_POST['descricaoProduto'] : '';
            $tags = !empty($_POST['tagsProduto']) ? $_POST['tagsProduto'] : '';
            $imagem = !empty($_POST['imagemProduto']) ? $_POST['imagemProduto'] : '';
            
            $stmt = $conn->prepare("INSERT INTO produtos (nomeProduto, valorProduto, quantidadeProduto, tipoProduto, linhaProduto, descricaoProduto, imagem, tagsProduto, idAdm, vendasProduto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("sdisssssi", 
                $_POST['nomeProduto'],
                $_POST['valorProduto'],
                $_POST['quantidadeProduto'],
                $_POST['tipoProduto'],
                $linhaProduto,
                $descricao,
                $imagem,
                $tags,
                $_SESSION['idAdm']
            );
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Produto adicionado com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao adicionar produto: ' . $stmt->error]);
            }
            break;

        case 'updateProduct':
            // Validação dos campos obrigatórios
            if (empty($_POST['idProduto']) || empty($_POST['nomeProduto']) || empty($_POST['valorProduto']) || empty($_POST['quantidadeProduto']) || empty($_POST['tipoProduto'])) {
                echo json_encode(['success' => false, 'message' => 'Campos obrigatórios não preenchidos']);
                break;
            }
            
            $linhaProduto = !empty($_POST['linhaProduto']) ? $_POST['linhaProduto'] : 'Genérico';
            $descricao = !empty($_POST['descricaoProduto']) ? $_POST['descricaoProduto'] : '';
            $tags = !empty($_POST['tagsProduto']) ? $_POST['tagsProduto'] : '';
            $imagem = !empty($_POST['imagemProduto']) ? $_POST['imagemProduto'] : '';
            
            $stmt = $conn->prepare("UPDATE produtos SET nomeProduto=?, valorProduto=?, quantidadeProduto=?, tipoProduto=?, linhaProduto=?, descricaoProduto=?, imagem=?, tagsProduto=? WHERE idProduto=?");
            $stmt->bind_param("sdisssssi",
                $_POST['nomeProduto'],
                $_POST['valorProduto'],
                $_POST['quantidadeProduto'],
                $_POST['tipoProduto'],
                $linhaProduto,
                $descricao,
                $imagem,
                $tags,
                $_POST['idProduto']
            );
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar produto: ' . $stmt->error]);
            }
            break;

        case 'deleteProduct':
            if (empty($_POST['idProduto'])) {
                echo json_encode(['success' => false, 'message' => 'ID do produto não fornecido']);
                break;
            }
            
            $stmt = $conn->prepare("DELETE FROM produtos WHERE idProduto=?");
            $stmt->bind_param("i", $_POST['idProduto']);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Produto excluído com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao excluir produto: ' . $stmt->error]);
            }
            break;

        // PEDIDOS
        case 'updateOrderStatus':
            $stmt = $conn->prepare("UPDATE pedido SET status=? WHERE idPedido=?");
            $stmt->bind_param("si", $_POST['status'], $_POST['idPedido']);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso!']);
            break;

        case 'getOrderDetails':
            $stmt = $conn->prepare("
                SELECT p.*, u.nomeUsuario, u.emailUsuario, u.celularUsuario,
                       e.rua, e.numero, e.bairro, e.cidade, e.estado, e.cep
                FROM pedido p
                LEFT JOIN usuario u ON p.idUsuario = u.idUsuario
                LEFT JOIN endereco e ON p.idEndereco = e.idEndereco
                WHERE p.idPedido = ?
            ");
            $stmt->bind_param("i", $_GET['idPedido']);
            $stmt->execute();
            $pedido = $stmt->get_result()->fetch_assoc();

            // Buscar itens do pedido
            $stmt = $conn->prepare("
                SELECT ip.*, pr.nomeProduto, pr.imagem
                FROM item_pedido ip
                LEFT JOIN produtos pr ON ip.idProduto = pr.idProduto
                WHERE ip.idPedido = ?
            ");
            $stmt->bind_param("i", $_GET['idPedido']);
            $stmt->execute();
            $itens = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['success' => true, 'pedido' => $pedido, 'itens' => $itens]);
            break;

        // USUÁRIOS
        case 'addUser':
            // Validação dos campos obrigatórios
            if (empty($_POST['nomeUsuario']) || empty($_POST['sobrenomeUsuario']) || empty($_POST['emailUsuario']) || empty($_POST['senhaUsuario'])) {
                echo json_encode(['success' => false, 'message' => 'Campos obrigatórios não preenchidos']);
                break;
            }
            
            // Verificar se email já existe
            $checkEmail = $conn->prepare("SELECT idUsuario FROM usuario WHERE emailUsuario = ?");
            $checkEmail->bind_param("s", $_POST['emailUsuario']);
            $checkEmail->execute();
            $checkEmail->store_result();
            
            if ($checkEmail->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado']);
                break;
            }
            
            // Hash da senha
            $hashedPassword = password_hash($_POST['senhaUsuario'], PASSWORD_DEFAULT);
            
            $cpf = !empty($_POST['cpfUsuario']) ? $_POST['cpfUsuario'] : NULL;
            $celular = !empty($_POST['celularUsuario']) ? $_POST['celularUsuario'] : '';
            $nascimento = !empty($_POST['nascimentoUsuario']) ? $_POST['nascimentoUsuario'] : '0000-00-00';
            
            $stmt = $conn->prepare("INSERT INTO usuario (nomeUsuario, sobrenomeUsuario, emailUsuario, senhaUsuario, cpfUsuario, celularUsuario, nascimentoUsuario, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssss", 
                $_POST['nomeUsuario'],
                $_POST['sobrenomeUsuario'],
                $_POST['emailUsuario'],
                $hashedPassword,
                $cpf,
                $celular,
                $nascimento
            );
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Usuário criado com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar usuário: ' . $stmt->error]);
            }
            break;

        case 'deleteUser':
            if (empty($_POST['idUsuario'])) {
                echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido']);
                break;
            }
            
            // Não permitir excluir o próprio usuário admin logado
            if (isset($_SESSION['idUsuario']) && $_POST['idUsuario'] == $_SESSION['idUsuario']) {
                echo json_encode(['success' => false, 'message' => 'Você não pode excluir seu próprio usuário']);
                break;
            }
            
            $idUsuario = $_POST['idUsuario'];
            
            // Iniciar transação para garantir integridade
            $conn->begin_transaction();
            
            try {
                // 1. Buscar todos os endereços do usuário
                $stmtEnderecos = $conn->prepare("SELECT idEndereco FROM endereco WHERE idUsuario = ?");
                $stmtEnderecos->bind_param("i", $idUsuario);
                $stmtEnderecos->execute();
                $resultEnderecos = $stmtEnderecos->get_result();
                $enderecos = [];
                while ($row = $resultEnderecos->fetch_assoc()) {
                    $enderecos[] = $row['idEndereco'];
                }
                
                // 2. Deletar itens de pedidos relacionados aos pedidos do usuário
                $conn->query("DELETE ip FROM item_pedido ip 
                             INNER JOIN pedido p ON ip.idPedido = p.idPedido 
                             WHERE p.idUsuario = $idUsuario");
                
                // 3. Deletar pedidos do usuário
                $stmtPedidos = $conn->prepare("DELETE FROM pedido WHERE idUsuario = ?");
                $stmtPedidos->bind_param("i", $idUsuario);
                $stmtPedidos->execute();
                
                // 4. Deletar solicitações de montagem
                $stmtMontagens = $conn->prepare("DELETE FROM servico_montagem WHERE idUsuario = ?");
                $stmtMontagens->bind_param("i", $idUsuario);
                $stmtMontagens->execute();
                
                // 5. Deletar endereços do usuário
                $stmtDelEnderecos = $conn->prepare("DELETE FROM endereco WHERE idUsuario = ?");
                $stmtDelEnderecos->bind_param("i", $idUsuario);
                $stmtDelEnderecos->execute();
                
                // 6. Deletar itens do carrinho
                $stmtCarrinho = $conn->prepare("DELETE FROM carrinho WHERE idUsuario = ?");
                $stmtCarrinho->bind_param("i", $idUsuario);
                $stmtCarrinho->execute();
                
                // 7. Finalmente, deletar o usuário
                $stmtUsuario = $conn->prepare("DELETE FROM usuario WHERE idUsuario = ?");
                $stmtUsuario->bind_param("i", $idUsuario);
                $stmtUsuario->execute();
                
                // Commit da transação
                $conn->commit();
                
                echo json_encode(['success' => true, 'message' => 'Usuário e todos os dados relacionados foram excluídos com sucesso!']);
                
            } catch (Exception $e) {
                // Rollback em caso de erro
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Erro ao excluir usuário: ' . $e->getMessage()]);
            }
            break;

        case 'getUserDetails':
            $stmt = $conn->prepare("
                SELECT u.*, 
                       (SELECT COUNT(*) FROM pedido WHERE idUsuario = u.idUsuario) as totalPedidos,
                       (SELECT SUM(total) FROM pedido WHERE idUsuario = u.idUsuario) as totalGasto
                FROM usuario u
                WHERE u.idUsuario = ?
            ");
            $stmt->bind_param("i", $_GET['idUsuario']);
            $stmt->execute();
            $usuario = $stmt->get_result()->fetch_assoc();
            echo json_encode(['success' => true, 'usuario' => $usuario]);
            break;

        // MONTAGENS - CORRIGIDO
        case 'deleteBuild':
            $stmt = $conn->prepare("DELETE FROM servico_montagem WHERE idMontagem=?");
            $stmt->bind_param("i", $_POST['idMontagem']);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Solicitação excluída com sucesso!']);
            break;

        case 'getBuildDetails':
            $stmt = $conn->prepare("
                SELECT s.*, u.nomeUsuario, u.emailUsuario, u.celularUsuario
                FROM servico_montagem s
                LEFT JOIN usuario u ON s.idUsuario = u.idUsuario
                WHERE s.idMontagem = ?
            ");
            $stmt->bind_param("i", $_GET['idMontagem']);
            $stmt->execute();
            $build = $stmt->get_result()->fetch_assoc();
            echo json_encode(['success' => true, 'build' => $build]);
            break;

        // CONTATOS - CORRIGIDO
        case 'deleteMessage':
            $stmt = $conn->prepare("DELETE FROM contatos WHERE id=?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Mensagem excluída com sucesso!']);
            break;

        case 'markMessageRead':
            $stmt = $conn->prepare("UPDATE contatos SET lido=1 WHERE id=?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Mensagem marcada como lida!']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>
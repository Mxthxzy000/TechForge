<?php
require '../config.php';
require 'session-check.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        // PRODUTOS
        case 'addProduct':
            $stmt = $conn->prepare("INSERT INTO produtos (nomeProduto, valorProduto, quantidadeProduto, tipoProduto, linhaProduto, descricaoProduto, imagem, tagsProduto, idAdm) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdisssssi", 
                $_POST['nomeProduto'],
                $_POST['valorProduto'],
                $_POST['quantidadeProduto'],
                $_POST['tipoProduto'],
                $_POST['linhaProduto'] ?? 'Genérico',
                $_POST['descricaoProduto'],
                $_POST['imagem'],
                $_POST['tagsProduto'],
                $_SESSION['idAdm']
            );
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Produto adicionado com sucesso!']);
            break;

        case 'updateProduct':
            $stmt = $conn->prepare("UPDATE produtos SET nomeProduto=?, valorProduto=?, quantidadeProduto=?, tipoProduto=?, linhaProduto=?, descricaoProduto=?, imagem=?, tagsProduto=? WHERE idProduto=?");
            $stmt->bind_param("sdisssssi",
                $_POST['nomeProduto'],
                $_POST['valorProduto'],
                $_POST['quantidadeProduto'],
                $_POST['tipoProduto'],
                $_POST['linhaProduto'] ?? 'Genérico',
                $_POST['descricaoProduto'],
                $_POST['imagem'],
                $_POST['tagsProduto'],
                $_POST['idProduto']
            );
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
            break;

        case 'deleteProduct':
            $stmt = $conn->prepare("DELETE FROM produtos WHERE idProduto=?");
            $stmt->bind_param("i", $_POST['idProduto']);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Produto excluído com sucesso!']);
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
            // Hash the password
            $hashedPassword = password_hash($_POST['senhaUsuario'], PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO usuario (nomeUsuario, sobrenomeUsuario, emailUsuario, senhaUsuario, cpfUsuario, celularUsuario, nascimentoUsuario, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssss", 
                $_POST['nomeUsuario'],
                $_POST['sobrenomeUsuario'],
                $_POST['emailUsuario'],
                $hashedPassword,
                $_POST['cpfUsuario'],
                $_POST['celularUsuario'],
                $_POST['nascimentoUsuario']
            );
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Usuário criado com sucesso!']);
            break;

        case 'deleteUser':
            $stmt = $conn->prepare("DELETE FROM usuario WHERE idUsuario=?");
            $stmt->bind_param("i", $_POST['idUsuario']);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Usuário excluído com sucesso!']);
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

        // MONTAGENS
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

        // CONTATOS
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

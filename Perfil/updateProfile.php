<?php
require '../config.php';
require '../session.php';
require '../flash.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'getUserData') {
    $stmt = $conn->prepare("SELECT nomeUsuario, sobrenomeUsuario, emailUsuario, celularUsuario, nascimentoUsuario FROM usuario WHERE idUsuario = ?");
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Usuário não encontrado']);
    }
    exit;
}

if ($action === 'updateProfile') {
    $nomeUsuario = trim($_POST['nomeUsuario'] ?? '');
    $sobrenomeUsuario = trim($_POST['sobrenomeUsuario'] ?? '');
    $emailUsuario = trim($_POST['emailUsuario'] ?? '');
    $celularUsuario = trim($_POST['celularUsuario'] ?? '');
    $nascimentoUsuario = trim($_POST['nascimentoUsuario'] ?? '');
    
    // Validações
    if (empty($nomeUsuario) || empty($sobrenomeUsuario) || empty($emailUsuario)) {
        echo json_encode(['error' => 'Nome, sobrenome e email são obrigatórios']);
        exit;
    }
    
    if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Email inválido']);
        exit;
    }
    
    // Check if email already exists for another user
    $stmt = $conn->prepare("SELECT idUsuario FROM usuario WHERE emailUsuario = ? AND idUsuario != ?");
    $stmt->bind_param('si', $emailUsuario, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['error' => 'Este email já está em uso por outro usuário']);
        exit;
    }
    
    // Update user data
    $sql = "UPDATE usuario SET 
            nomeUsuario = ?, 
            sobrenomeUsuario = ?, 
            emailUsuario = ?, 
            celularUsuario = ?, 
            nascimentoUsuario = ?
            WHERE idUsuario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $nomeUsuario, $sobrenomeUsuario, $emailUsuario, $celularUsuario, $nascimentoUsuario, $idUsuario);
    
    if ($stmt->execute()) {
        // Update session data
        $_SESSION['nomeUsuario'] = $nomeUsuario;
        $_SESSION['emailUsuario'] = $emailUsuario;
        
        echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao atualizar perfil']);
    }
    exit;
}

if ($action === 'updatePassword') {
    $senhaAtual = $_POST['senhaAtual'] ?? '';
    $novaSenha = $_POST['novaSenha'] ?? '';
    $confirmarSenha = $_POST['confirmarSenha'] ?? '';
    
    if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
        echo json_encode(['error' => 'Todos os campos são obrigatórios']);
        exit;
    }
    
    if ($novaSenha !== $confirmarSenha) {
        echo json_encode(['error' => 'As senhas não coincidem']);
        exit;
    }
    
    if (strlen($novaSenha) < 6) {
        echo json_encode(['error' => 'A senha deve ter no mínimo 6 caracteres']);
        exit;
    }
    
    // Verify current password
    $stmt = $conn->prepare("SELECT senhaUsuario FROM usuario WHERE idUsuario = ?");
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!password_verify($senhaAtual, $user['senhaUsuario'])) {
        echo json_encode(['error' => 'Senha atual incorreta']);
        exit;
    }
    
    // Update password
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE usuario SET senhaUsuario = ? WHERE idUsuario = ?");
    $stmt->bind_param('si', $novaSenhaHash, $idUsuario);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Senha alterada com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao alterar senha']);
    }
    exit;
}

echo json_encode(['error' => 'Ação inválida']);
exit;
?>

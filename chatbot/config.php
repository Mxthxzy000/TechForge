<?php
class ChatBot {
    private $api_url = 'https://api.algion.dev/v1/chat/completions';
    private $api_key = '123123'; // Substitua pela sua chave real
    private $model = 'gpt-4o';
    private $conversation_history = [];

    public function __construct($api_key = null) {
        if ($api_key) {
            $this->api_key = $api_key;
        }
        
        // Inicializa com uma mensagem do sistema (opcional)
        $this->addSystemMessage("Você é um assistente útil e amigável.");
    }

    public function addSystemMessage($content) {
        $this->conversation_history[] = [
            'role' => 'system',
            'content' => $content
        ];
    }

    public function addUserMessage($content) {
        $this->conversation_history[] = [
            'role' => 'user',
            'content' => $content
        ];
    }

    public function addAssistantMessage($content) {
        $this->conversation_history[] = [
            'role' => 'assistant',
            'content' => $content
        ];
    }

    public function sendMessage($user_message) {
        // Adiciona a mensagem do usuário ao histórico
        $this->addUserMessage($user_message);

        // Prepara os dados para a API
        $data = [
            'model' => $this->model,
            'messages' => $this->conversation_history
        ];

        // Configura e executa a requisição cURL
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception("Erro cURL: " . $error_msg);
        }
        
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception("Erro HTTP: " . $http_code . " - " . $response);
        }

        $result = json_decode($response, true);
        
        if (!isset($result['choices'][0]['message']['content'])) {
            throw new Exception("Resposta da API inválida: " . $response);
        }

        $assistant_response = $result['choices'][0]['message']['content'];
        
        // Adiciona a resposta do assistente ao histórico
        $this->addAssistantMessage($assistant_response);

        return $assistant_response;
    }

    public function getConversationHistory() {
        return $this->conversation_history;
    }

    public function clearHistory() {
        $this->conversation_history = [];
    }

    public function setModel($model) {
        $this->model = $model;
    }
}

// Exemplo de uso como interface web
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    header('Content-Type: application/json');
    
    try {
        // Inicializa o chatbot (pode usar sessão para manter histórico)
        session_start();
        if (!isset($_SESSION['chatbot'])) {
            $_SESSION['chatbot'] = new ChatBot();
            // Opcional: definir personalidade do bot
            $_SESSION['chatbot']->addSystemMessage("Você é um assistente útil que responde em português.");
        }

        $chatbot = $_SESSION['chatbot'];
        $user_message = trim($_POST['message']);
        
        if (empty($user_message)) {
            throw new Exception("Mensagem vazia");
        }

        $response = $chatbot->sendMessage($user_message);
        
        echo json_encode([
            'success' => true,
            'response' => $response,
            'history' => $chatbot->getConversationHistory()
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}
?>
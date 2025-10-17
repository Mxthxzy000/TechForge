<?php
// flash.php
// Remove session_start() - já feito em session.php

if (!function_exists('set_flash')) {
    function set_flash($tipo, $msg) {
        $_SESSION['flash'] = [
            'tipo' => $tipo,
            'msg' => $msg
        ];
    }
}

if (!function_exists('show_flash')) {
    function show_flash() {
        if (!isset($_SESSION['flash'])) return;

        $f = $_SESSION['flash'];
        $tipo = isset($f['tipo']) ? strtolower($f['tipo']) : 'aviso';
        $msg = htmlspecialchars($f['msg'] ?? '', ENT_QUOTES, 'UTF-8');  // ✅ ESCAPA XSS

        $corBorda = "#f8c10d";
        $corFundo = "#2B3640";

        switch ($tipo) {
            case 'erro':
            case 'error':
                $corBorda = "#d9534f";
                $corFundo = "#3c1e1e";
                break;
            case 'sucesso':
            case 'success':
                $corBorda = "#1a8f1a";
                $corFundo = "#1a2a3c";
                break;
            case 'aviso':
            case 'warning':
                $corBorda = "#f8c10d";
                $corFundo = "#3c2f1a";
                break;
        }

        $tipoEscapado = htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8');

        echo <<<HTML
        <style>
            .flash-message {
                font-family: 'Inter', sans-serif;
                background-color: {$corFundo};
                border-left: 5px solid {$corBorda};
                color: #fffbf3;
                padding: 14px 18px;
                border-radius: 10px;
                margin: 15px auto;
                width: 80%;
                max-width: 700px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                animation: fadeIn 0.6s ease-out;
            }

            .flash-message strong {
                color: {$corBorda};
                text-transform: uppercase;
                margin-right: 6px;
            }

            .flash-close {
                cursor: pointer;
                font-size: 20px;
                font-weight: bold;
                color: #fffbf3;
                background: none;
                border: none;
                transition: transform 0.3s ease;
            }

            .flash-close:hover {
                transform: scale(1.3);
                color: {$corBorda};
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        <div class='flash-message'>
            <div>
                <strong>{$tipoEscapado}:</strong> {$msg}
            </div>
            <button class='flash-close' onclick='this.parentElement.remove()'>&times;</button>
        </div>
HTML;

        unset($_SESSION['flash']);
    }
}
?>
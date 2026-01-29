# TechForge - E-commerce de Hardware e Tecnologia

<div align="center">
  <img src="imagens/logo_header_TechForge.png" alt="TechForge Logo" width="300"/>
  
  **Projeto Educacional - SENAI 2024**
  
  *E-commerce inspirado na Kabum e Terabyte com design gamer*
</div>

---

## Aviso Importante

> **Este projeto foi desenvolvido exclusivamente para fins educacionais como trabalho semestral do SENAI.**  
> Nenhum produto, preço ou informacao apresentada neste site e real.  
> Nenhuma transacao comercial e realizada.  
> Este nao e um e-commerce funcional para vendas reais.

---

## Sobre o Projeto

O **TechForge** e um e-commerce ficticio de hardware e componentes de computador, desenvolvido como projeto semestral do curso tecnico do SENAI. O projeto simula uma loja virtual completa com design inspirado em grandes varejistas de tecnologia brasileiros como Kabum e Terabyte.

### Objetivo

Aplicar na pratica os conhecimentos adquiridos durante o curso, incluindo:
- Desenvolvimento web full-stack
- Integracao com banco de dados
- Sistemas de autenticacao e sessoes
- Design responsivo com tematica gamer
- Boas praticas de programacao

---

## Funcionalidades

### Area do Cliente

| Funcionalidade | Descricao |
|----------------|-----------|
| **Home Page** | Pagina inicial com slider de banners, produtos mais vendidos e destaques |
| **Catalogo de Produtos** | Listagem completa com filtros por preco, categoria, marca e tags |
| **Detalhes do Produto** | Pagina individual com informacoes detalhadas, avaliacoes e parcelamento |
| **Carrinho de Compras** | Sistema completo de carrinho com adicao, remocao e atualizacao de quantidade |
| **Checkout** | Processo de finalizacao de compra com selecao de endereco e forma de pagamento |
| **Monte Seu PC** | Ferramenta interativa para montagem personalizada de computadores |
| **Perfil do Usuario** | Gerenciamento de dados pessoais, enderecos, formas de pagamento e historico de pedidos |
| **ChatBot** | Assistente virtual para suporte ao cliente |
| **Fale Conosco** | Formulario de contato para duvidas e sugestoes |
| **Sobre Nos** | Pagina institucional da empresa |

### Painel Administrativo

| Funcionalidade | Descricao |
|----------------|-----------|
| **Dashboard** | Visao geral com estatisticas de produtos, usuarios, pedidos e montagens |
| **Gestao de Produtos** | CRUD completo de produtos com upload de imagens |
| **Gestao de Pedidos** | Visualizacao e atualizacao de status dos pedidos |
| **Gestao de Usuarios** | Administracao dos usuarios cadastrados |
| **Solicitacoes de Montagem** | Gerenciamento das solicitacoes do "Monte Seu PC" |
| **Mensagens de Contato** | Central de atendimento para mensagens recebidas |

---

## Tecnologias Utilizadas

### Front-end
- **HTML5** - Estrutura semantica
- **CSS3** - Estilizacao com design responsivo
- **JavaScript** - Interatividade e requisicoes AJAX
- **Ionicons** - Biblioteca de icones
- **SweetAlert2** - Alertas e notificacoes estilizadas

### Back-end
- **PHP 8** - Logica do servidor e APIs
- **MySQL/MariaDB** - Banco de dados relacional
- **PHPMyAdmin** - Administracao do banco de dados

### Design
- **Paleta de cores gamer** - Tons escuros com acentos em vermelho/laranja
- **Layout responsivo** - Adaptavel a diferentes dispositivos
- **Inspiracao** - Kabum e Terabyte

---

## Estrutura do Projeto

```
TechForge/
├── Admin/                    # Painel administrativo
│   ├── admin.css/js          # Estilos e scripts do admin
│   ├── index.php             # Dashboard principal
│   ├── produtos.php          # Gestao de produtos
│   ├── pedidos.php           # Gestao de pedidos
│   ├── usuarios.php          # Gestao de usuarios
│   ├── montagens.php         # Solicitacoes de montagem
│   └── contatos.php          # Mensagens de contato
│
├── Carrinho/                 # Sistema de carrinho
│   ├── carrinho.php          # Pagina do carrinho
│   └── cartAPI.php           # API do carrinho
│
├── Catalogo/                 # Listagem de produtos
│   ├── catalogo.php          # Pagina do catalogo
│   └── funcionalidadesCatalogo.php  # Funcoes de filtro
│
├── Checkout/                 # Finalizacao de compra
│   ├── out.php               # Pagina de checkout
│   └── checkoutAPI.php       # API de pedidos
│
├── Comum/                    # Arquivos compartilhados
│   ├── common.css            # Estilos globais
│   └── common.js             # Scripts globais
│
├── Detalhes/                 # Pagina de detalhes do produto
│   └── detalhes.php
│
├── Fale Conosco/             # Formulario de contato
│   └── fale.php
│
├── Home/                     # Pagina inicial
│   ├── index.php             # Home page
│   ├── style.css             # Estilos da home
│   └── script.js             # Scripts da home
│
├── MontarPC/                 # Ferramenta de montagem
│   ├── montarpc.php          # Pagina principal
│   └── selecionar-componente.php  # Selecao de pecas
│
├── Perfil/                   # Area do usuario
│   ├── perfil.php            # Pagina de perfil
│   ├── addressAPI.php        # API de enderecos
│   └── ordersAPI.php         # API de pedidos
│
├── Sobre/                    # Pagina institucional
│   └── sobre.php
│
├── cadastro/                 # Registro de usuarios
│   └── cadastro.php
│
├── chatbot/                  # Assistente virtual
│   └── chatbot.php
│
├── login/                    # Autenticacao
│   └── login.php
│
├── imagens/                  # Imagens do sistema
├── imagens_produtos/         # Fotos dos produtos
│
├── text/                     # Scripts SQL
│   └── techforge_db.sql      # Estrutura do banco
│
├── config.php                # Configuracoes do banco
├── session.php               # Gerenciamento de sessoes
├── flash.php                 # Sistema de mensagens
└── logout.php                # Encerramento de sessao
```

---

## Banco de Dados

### Tabelas Principais

| Tabela | Descricao |
|--------|-----------|
| `usuario` | Cadastro de clientes |
| `administrador` | Cadastro de administradores |
| `produtos` | Catalogo de produtos |
| `carrinho` | Carrinhos de compra |
| `item_carrinho` | Itens dos carrinhos |
| `pedido` | Pedidos realizados |
| `item_pedido` | Itens dos pedidos |
| `endereco` | Enderecos de entrega |
| `formas_pagamento` | Metodos de pagamento salvos |
| `servico_montagem` | Solicitacoes do Monte Seu PC |
| `avaliacao_produto` | Avaliacoes dos clientes |
| `contatos` | Mensagens do Fale Conosco |

---

## Como Executar

### Pre-requisitos
- **XAMPP**, **WAMP** ou **LAMP** (PHP 8+ e MySQL/MariaDB)
- Navegador web moderno

### Instalacao

1. **Clone o repositorio** na pasta do servidor web:
   ```bash
   # Para XAMPP (Windows)
   cd C:/xampp/htdocs
   git clone https://github.com/seu-usuario/techforge.git
   
   # Para LAMP (Linux)
   cd /var/www/html
   git clone https://github.com/seu-usuario/techforge.git
   ```

2. **Importe o banco de dados**:
   - Acesse o PHPMyAdmin (http://localhost/phpmyadmin)
   - Crie um banco de dados chamado `techforge_db`
   - Importe o arquivo `text/techforge_db (oficial).sql`

3. **Configure a conexao** no arquivo `config.php`:
   ```php
   $host = "localhost";
   $user = "root";
   $password = "";  // Senha do MySQL (vazio no XAMPP)
   $database = "techforge_db";
   ```

4. **Acesse o projeto**:
   - Site: http://localhost/techforge/Home/index.php
   - Admin: http://localhost/techforge/Admin/login.php

### Credenciais de Teste

**Painel Admin:**
- Email: `adminTechforge@gmail.com`
- Chave: `ADM123`

---

## Screenshots

### Pagina Inicial
> Design moderno com slider de banners e produtos em destaque

### Catalogo de Produtos
> Sistema de filtros avancados por preco, marca e categoria

### Monte Seu PC
> Ferramenta interativa para configurar computadores personalizados

### Painel Administrativo
> Dashboard completo com gestao de produtos, pedidos e usuarios

---

## Equipe de Desenvolvimento

**Projeto desenvolvido por estudantes do SENAI**

- Desenvolvimento Full-Stack
- Design UI/UX
- Banco de Dados
- Documentacao

---

## Licenca e Uso

Este projeto e de carater **estritamente educacional**:

- Pode ser usado como referencia para estudos
- Pode ser modificado para fins de aprendizado
- **NAO** deve ser utilizado para fins comerciais
- **NAO** e um e-commerce real - nenhum produto esta a venda

---

## Agradecimentos

- **SENAI** - Pela oportunidade de aprendizado
- **Kabum e Terabyte** - Inspiracao para o design
- **Professores e colegas** - Pelo apoio durante o desenvolvimento

---

<div align="center">
  <strong>TechForge - Projeto Educacional SENAI 2024</strong>
  <br>
  <em>Desenvolvido com dedicacao para fins academicos</em>
</div>

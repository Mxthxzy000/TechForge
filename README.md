
# TechForge – E-commerce de Tecnologia

**Projeto educacional (SENAI 2024)** que simula um **e-commerce de hardware e tecnologia**, inspirado em lojas como Kabum e Terabyte, com visual gamer.

⚠️ **Projeto fictício**: não realiza vendas reais e não possui transações comerciais.

---

# O que o projeto faz

* Simula uma loja virtual completa
* Catálogo de produtos de tecnologia
* Carrinho de compras e checkout
* Sistema de usuários e sessões
* Painel administrativo
* Ferramenta “Monte seu PC”

---

# Funcionalidades principais

### Área do cliente

* Home com banners e destaques
* Catálogo com filtros
* Página de detalhes do produto
* Carrinho de compras
* Checkout
* Monte seu PC
* Perfil do usuário
* Chatbot
* Fale Conosco

### Área administrativa

* Dashboard com estatísticas
* CRUD de produtos
* Gerenciamento de pedidos
* Gerenciamento de usuários
* Solicitações de montagem
* Mensagens de contato

---

# Tecnologias utilizadas

**Front-end**

* HTML5
* CSS3
* JavaScript
* SweetAlert2
* Ionicons

**Back-end**

* PHP 8
* MySQL / MariaDB
* phpMyAdmin

---

# Estrutura do projeto (resumida)

TechForge/
├── Home/
├── Catalogo/
├── Carrinho/
├── Checkout/
├── MontarPC/
├── Perfil/
├── Admin/
├── login/
├── cadastro/
├── chatbot/
├── imagens/
├── config.php
├── session.php
└── techforge_db.sql

---

# Banco de dados

Inclui tabelas para:

* Usuários e administradores
* Produtos
* Carrinho e pedidos
* Endereços e pagamentos
* Monte seu PC
* Avaliações
* Contatos

---

# Como executar

1. Coloque o projeto na pasta do servidor (`htdocs` ou `/var/www`)
2. Crie o banco `techforge_db`
3. Importe `techforge_db.sql`
4. Configure `config.php`
5. Acesse:

   * Loja: `http://localhost/techforge/Home`
   * Admin: `http://localhost/techforge/Admin`

---

# Conceitos praticados

* PHP full-stack
* CRUD avançado
* Sessões e autenticação
* Organização de projeto grande
* Integração com banco de dados
* Design responsivo
* Separação de áreas (cliente/admin)

---

# Aviso

Projeto **100% educacional**.
Não deve ser usado para fins comerciais.

---

# Equipe

Projeto desenvolvido por **estudantes do SENAI** para fins acadêmicos.

---

# Licença

Uso livre **apenas para estudo**.

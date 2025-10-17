<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sobre.css">
    <title>Sobre</title>
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
            <button id="minha-conta" class="btn-header"><ion-icon name="person-circle-outline"></ion-icon></button>
            <button id="carrinho" class="btn-header"><ion-icon name="cart-outline"></ion-icon></button>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="../Home/index.php" class="voltarInicio"><ion-icon name="arrow-back-circle-outline"></ion-icon>INÍCIO</a></li>
        </ul>
    </nav>    
    <div class="container-new">
        <h1>SOBRE A TECHFORGE</h1>

        <div class="about-text">
            <p>
                Fundada com o intuito de transformar a experiência tecnológica no Brasil, a TechForge é referência em inovação e excelência no mercado de tecnologia e games. Nossa missão é proporcionar aos nossos clientes acesso aos melhores produtos e serviços, sempre com foco em qualidade, atendimento personalizado e suporte técnico especializado.
            </p>
            <p>
                Nascida da paixão por tecnologia e inovação, começamos nossa jornada com o objetivo de democratizar o acesso a equipamentos de alta performance. Hoje, somos reconhecidos como uma das principais referências no segmento, oferecendo desde componentes de hardware até soluções completas para gamers, profissionais e entusiastas.
            </p>
            <p>
                Nossa equipe é formada por especialistas apaixonados por tecnologia, sempre atualizados com as últimas tendências do mercado. Acreditamos que cada cliente é único, e por isso oferecemos consultoria personalizada para garantir que você encontre exatamente o que precisa, seja para montar seu setup dos sonhos ou para atualizar seu equipamento.
            </p>
            <p>
                Além de produtos de qualidade, investimos constantemente em infraestrutura e capacitação para oferecer a melhor experiência de compra. Nossas lojas físicas são projetadas para que você possa conhecer, testar e comparar produtos antes de decidir. E nosso suporte técnico está sempre disponível para auxiliar em qualquer dúvida ou necessidade.
            </p>
            <p>
                Na TechForge, acreditamos que tecnologia é mais do que produtos - é sobre possibilitar experiências, realizar sonhos e impulsionar o futuro. Venha fazer parte dessa jornada conosco!
            </p>
        </div>

        <div class="highlight-section">
            <h2>Desde Agosto de 2025, referência em<br>Tecnologia e Games no Brasil</h2>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Colaboradores</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">💡</div>
                    <div class="stat-label">Inovação<br>constante</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">👤</div>
                    <div class="stat-label">Atendimento<br>personalizado</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📧</div>
                    <div class="stat-label">Suporte<br>24/7</div>
                </div>
            </div>
        </div>

        <h3>Estrutura Física</h3>

        <div class="structure-grid">
            <div class="structure-card">
                <div class="structure-image img-1"></div>
                <div class="structure-caption">TechForge - Loja Matriz - São Paulo, SP</div>
            </div>

            <div class="structure-card">
                <div class="structure-image img-2"></div>
                <div class="structure-caption">TechForge - Filial Shopping - Campinas, SP</div>
            </div>

            <div class="structure-card">
                <div class="structure-image img-3"></div>
                <div class="structure-caption">TechForge - Centro de Distribuição - Jundiaí, SP</div>
            </div>

            <div class="structure-card">
                <div class="structure-image img-4"></div>
                <div class="structure-caption">Blades - Parceiro Autorizado - Curitiba, PR</div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container-footer">
            <ul>
                <h3>TECHFORGE</h3>
                <div class="links">
                    <li><a href="#">Sobre nós</a></li>
                    <li><a href="#">Política De Privacidade</a></li>
                    <li><a href="#">Parceiros</a></li>
                </div>
            </ul>

            <ul>
                <h3>AJUDA</h3>
                <div class="links">
                    <li><a href="#">Fale Conosco</a></li>
                    <li><a href="#">Chat Suporte</a></li>
                    <li><a href="#">Sua Conta</a></li>
                </div>
            </ul>

            <ul>
                <h3>SERVIÇOS</h3>
                <div class="links">
                    <li><a href="#">Catálogo</a></li>
                    <li><a href="#">Suporte</a></li>
                    <li><a href="#">Como Escolher</a></li>
                </div>
            </ul>

            <ul>
                <h3>SIGA-NOS</h3>
                <div class="links-icon">
                    <ion-icon name="logo-instagram"></ion-icon>
                    <ion-icon name="logo-twitter"></ion-icon>
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

    <script src="sobre.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
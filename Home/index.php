<<<<<<< HEAD
<?php 
    include("../config.php");
?>
>>>>>>> 763bf84fd61a3a3dd9defbaf5f99d96d8d3b2ba9
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechForge</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
            <div class="divpesquisar">
                <button id="pesquisar" class="btn-pesquisar"><ion-icon name="search-sharp"></ion-icon></button>
                <input type="text" placeholder=" Pesquisar..." class="barra-pesquisa">
            </div>
            <div class="usuario-menu">
                <button id="minha-conta" class="btn-header">
                    <ion-icon name="person-circle-outline"></ion-icon>
                </button>
            </div>
            <button id="carrinho" class="btn-header"><ion-icon name="cart-outline"></ion-icon></button>
        </div>
    </header>

    <div class="dropdown-user">
<<<<<<< HEAD
        <a href="../Login/login.php" class="menu-usuario">
=======
        <a href="../Login/login.html" class="menu-usuario">
>>>>>>> 763bf84fd61a3a3dd9defbaf5f99d96d8d3b2ba9
            Minha Conta
            <ion-icon name="person-circle-outline" class="icon-user"></ion-icon>
        </a>

        <div class="menu-usuario">
            <span>Ativar Modo Escuro</span>
            <label class="switch">
                <input type="checkbox" id="botao-modo-escuro">
                <span class="slider-dark"></span>
            </label>
        </div>

        <button class="menu-usuario">Sair</button>
    </div>



    <nav>
        <ul>
            <li><a href="#">HARDWARES</a></li>
            <span class="linha"></span>
            <li><a href="#">OFERTAS</a></li>
            <span class="linha"></span>
            <li><a href="#">MONTE SEU PC</a></li>
            <span class="linha"></span>
            <li><a href="#">GAMER</a></li>
            <span class="linha"></span>
            <li><a href="#">SOBRE NÓS</a></li>
        </ul>
    </nav>

    <main>
        <div class="slider">
            <div class="slides">
                <input type="radio" name="radio-btn" id="radio1">
                <input type="radio" name="radio-btn" id="radio2">
                <input type="radio" name="radio-btn" id="radio3">
                <input type="radio" name="radio-btn" id="radio4">

                <div class="slide first">
                    <img src="../imagens/banner parceria com a karol.png" alt="Slide 1">
                </div>

                <div class="slide">
                    <img src="../imagens/banner rick sanchez.png" alt="Slide2">
                </div>

                <div class="slide">
                    <img src="../imagens/banner vermelho.png" alt="Slide3">
                </div>

                <div class="slide">
                    <img src="../imagens/banner vermelho.png" alt="Slide4">
                </div>

                <div class="autonavegar">
                    <div class="auto-btn1"></div>
                    <div class="auto-btn2"></div>
                    <div class="auto-btn3"></div>
                    <div class="auto-btn4"></div>
                </div>

                <div class="manual-navegar">
                    <label for="radio1" class="manual-btn"></label>
                    <label for="radio2" class="manual-btn"></label>
                    <label for="radio3" class="manual-btn"></label>
                    <label for="radio4" class="manual-btn"></label>
                </div>
            </div>
        </div>
        </div>
<<<<<<< HEAD
=======

        <div class="mincard">

            <div class="card1">
                <h2>PREÇOS BAIXOS</h2>
                <img src="../imagens/preçosBaixos.png" alt="preços baixos" id="preços-baixos">
            </div>

            <div class="card1">
                <h2>SERVIÇOS DE MONTAGEM</h2>
                <img src="../imagens/serviçoMontagem.png" alt="serviços de montagem" id="serviços-montagem">
            </div>

            <div class="card1">
                <h2>EXCELENTE QUALIDADE</h2>
                <img src="../imagens/excelenteQualidade.png" alt="excelente qualidade" id="excelente-qualidade">
            </div>

            <div class="card1">
                <h2>CUPONS DE DESCONTO</h2>
                <img src="../imagens/cuponsDesconto.png" alt=" cupons de desconto" id="cupons-desconto">
            </div>

            <div class="card1">
                <h2>PARCELAS DE ATÉ 12X</h2>
                <img src="../imagens/parcelas12x.png" alt="parcelas de até 12x" id="parcelas-12x">
            </div>
        </div>

        <div class="cards-produtos">
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
            <div class="card-produto">
                <span class="badge">Mais Vendidos</span>
                <img src="https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/1/0/100-100000926wof.jpg"
                    alt="Produto">

                <div class="card-info">
                    <h3>Processador AMD Ryzen 7 5700X</h3>
                    <p class="descricao">3.4GHz (4.6GHz Turbo), 8-Cores 16-Threads, AM4</p>

                    <p class="preco-antigo">De: R$ 2.599,99</p>
                    <p class="preco-atual">R$ 1.299,90 <span>à vista</span></p>
                    <p class="parcelamento">12x de R$ 127,44 sem juros</p>
                </div>

                <button class="btn-comprar-produto">Comprar</button>
            </div>
        </div>

>>>>>>> 763bf84fd61a3a3dd9defbaf5f99d96d8d3b2ba9
    </main>

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
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
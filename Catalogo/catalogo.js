// Ler tag da URL e aplicar filtro automaticamente
const urlParams = new URLSearchParams(window.location.search);
const tagFromURL = urlParams.get('tag');
if (tagFromURL) {
    currentFilters.tag = tagFromURL;

    // Marca o checkbox correspondente, se existir
    const checkbox = document.querySelector(`.tipoFiltro[data-tag="${tagFromURL}"]`);
    if (checkbox) checkbox.checked = true;

    applyFilters();
}

document.addEventListener('DOMContentLoaded', function() {
    let currentFilters = {
        linha: '',
        tag: '',
        precoMin: '',
        precoMax: ''
    };

    // Elementos da pesquisa
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.querySelector('.btn-pesquisar');
    const suggestionsList = document.getElementById('suggestions');

    // Carregar todos os produtos inicialmente
    loadProducts();

    // Eventos da pesquisa
    searchInput.addEventListener('input', function() {
        const termo = this.value.trim();
        if (termo.length >= 2) {
            showSuggestions(termo);
        } else {
            hideSuggestions();
        }
    });

    searchInput.addEventListener('focus', function() {
        const termo = this.value.trim();
        if (termo.length >= 2) {
            showSuggestions(termo);
        }
    });

    searchButton.addEventListener('click', function() {
        performSearch(searchInput.value.trim());
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch(this.value.trim());
        }
    });

    // Fechar sugestões ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.divpesquisar')) {
            hideSuggestions();
        }
    });

    // Função para mostrar sugestões
    function showSuggestions(termo) {
        fetch(`funcionalidadesCatalogo.php?action=search&termo=${encodeURIComponent(termo)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro:', data.error);
                    return;
                }
                
                if (data.produtos.length > 0) {
                    suggestionsList.innerHTML = data.produtos.map(product => `
                        <li data-id="${product.idProduto}" data-name="${product.nomeProduto}">
                            ${product.nomeProduto}
                        </li>
                    `).join('');
                    suggestionsList.style.display = 'block';
                    
                    // Adicionar eventos às sugestões
                    suggestionsList.querySelectorAll('li').forEach(item => {
                        item.addEventListener('click', function() {
                            searchInput.value = this.getAttribute('data-name');
                            hideSuggestions();
                            performSearch(this.getAttribute('data-name'));
                        });
                    });
                } else {
                    hideSuggestions();
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }

    // Função para esconder sugestões
    function hideSuggestions() {
        suggestionsList.style.display = 'none';
    }

    // Função para executar pesquisa completa
    function performSearch(termo) {
        hideSuggestions();
        
        if (termo === '') {
            loadProducts();
            return;
        }

        fetch(`funcionalidadesCatalogo.php?action=searchFull&termo=${encodeURIComponent(termo)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro:', data.error);
                    return;
                }
                displayProducts(data.produtos);
                document.getElementById('pageTitle').textContent = `Resultados para: "${termo}"`;
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }

    // Filtros por linha (Intel/AMD)
    document.querySelectorAll('.marca-option').forEach(option => {
        option.addEventListener('click', function() {
            const linha = this.getAttribute('data-linha');
            
            // Toggle - se clicar na mesma linha, desmarca
            if (currentFilters.linha === linha) {
                currentFilters.linha = '';
                this.classList.remove('active');
            } else {
                currentFilters.linha = linha;
                document.querySelectorAll('.marca-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
            }
            
            applyFilters();
        });
    });

    // Filtros por tipo de peça
    document.querySelectorAll('.tipoFiltro').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const tag = this.getAttribute('data-tag');
            
            if (this.checked) {
                currentFilters.tag = tag;
                // Desmarca outros checkboxes
                document.querySelectorAll('.tipoFiltro').forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            } else {
                currentFilters.tag = '';
            }
            
            applyFilters();
        });
    });

    // Filtro por preço
    document.getElementById('applyPrice').addEventListener('click', function() {
        currentFilters.precoMin = document.getElementById('priceMin').value;
        currentFilters.precoMax = document.getElementById('priceMax').value;
        applyFilters();
    });

    // Aplicar filtros
    function applyFilters() {
        manterTamanhoFiltro();
        
        // Verifica se há algum filtro ativo
        const hasActiveFilter = currentFilters.linha || currentFilters.tag || currentFilters.precoMin || currentFilters.precoMax;
        
        if (!hasActiveFilter) {
            // Se não há filtros ativos, carrega todos os produtos
            loadProducts();
            document.getElementById('pageTitle').textContent = 'Produtos';
            return;
        }

        // Monta a URL com os filtros
        let url = `funcionalidadesCatalogo.php?action=filter`;
        
        if (currentFilters.linha) {
            url += `&linha=${encodeURIComponent(currentFilters.linha)}`;
        }
        
        if (currentFilters.tag) {
            url += `&tag=${encodeURIComponent(currentFilters.tag)}`;
        }
        
        if (currentFilters.precoMin) {
            url += `&precoMin=${encodeURIComponent(currentFilters.precoMin)}`;
        }
        
        if (currentFilters.precoMax) {
            url += `&precoMax=${encodeURIComponent(currentFilters.precoMax)}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro:', data.error);
                    return;
                }
                displayProducts(data.produtos);
                document.getElementById('pageTitle').textContent = 'Produtos Filtrados';
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }

    // Carregar todos os produtos
    function loadProducts() {
        fetch('funcionalidadesCatalogo.php?action=getAll')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro:', data.error);
                    return;
                }
                displayProducts(data.produtos);
                document.getElementById('pageTitle').textContent = 'Produtos';
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }

    // Exibir produtos - FORMATAÇÃO IDÊNTICA À HOME
    function displayProducts(products) {
        const container = document.getElementById('products');
        const resultCount = document.getElementById('resultCount');
        
        resultCount.textContent = `(${products.length} produtos)`;
        
        if (products.length === 0) {
            container.innerHTML = '<p>Nenhum produto encontrado.</p>';
            return;
        }

        container.innerHTML = products.map(product => `
            <div class="card-produto">
                <img src="../imagens_produtos/${product.imagemProduto}" alt="${product.nomeProduto}">
                <div class="card-info">
                    <h3>${product.nomeProduto}</h3>
                    <p class="preco-atual">
                        R$ ${parseFloat(product.precoProduto).toFixed(2).replace('.', ',')} <span>à vista</span>
                    </p>
                    <p class="parcelamento">
                        12x de R$ ${(parseFloat(product.precoProduto) / 12).toFixed(2).replace('.', ',')} sem juros
                    </p>
                </div>
                <button class="btn-comprar-produto" data-id="${product.idProduto}">Comprar</button>
            </div>
        `).join('');

        // Adicionar eventos aos botões do carrinho
        container.querySelectorAll('.btn-comprar-produto').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                addToCart(productId);
            });
        });
    }

    // Função para adicionar ao carrinho
    function addToCart(productId) {
        console.log('Adicionar produto ao carrinho:', productId);
        // Sua lógica existente para adicionar ao carrinho
    }

    // Forçar tamanho do filtro
    function manterTamanhoFiltro() {
        const filtro = document.querySelector('.filtro-lateral');
        if (filtro) {
            filtro.style.width = '300px';
            filtro.style.minWidth = '300px';
            filtro.style.flexShrink = '0';
        }
    }

    // Executar quando a DOM carregar
    manterTamanhoFiltro();
});
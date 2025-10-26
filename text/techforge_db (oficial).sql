-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/10/2025 às 14:09
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `techforge_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `nomeAdm` varchar(100) NOT NULL,
  `idAdm` int(11) NOT NULL,
  `emailAdm` varchar(255) NOT NULL,
  `chaveAdm` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`nomeAdm`, `idAdm`, `emailAdm`, `chaveAdm`) VALUES
('Administrador Geral TechForge', 1, 'adminTechforge@gmail.com', 'ADM123'),
('Administrador Substituto TechForge', 2, 'adminTechforge2@gmail.com', '123ADM');

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao_produto`
--

CREATE TABLE `avaliacao_produto` (
  `idAvaliacao` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `nota` tinyint(4) DEFAULT NULL CHECK (`nota` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `dataAvaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `idCarrinho` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `dataCriacao` datetime DEFAULT current_timestamp(),
  `status` enum('ativo','finalizado','cancelado') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`idCarrinho`, `idUsuario`, `dataCriacao`, `status`) VALUES
(1, 2, '2025-10-26 01:09:29', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `assunto` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `lido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `idEndereco` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `tipoEndereco` enum('entrega','cobranca','outro') DEFAULT 'entrega'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `endereco`
--

INSERT INTO `endereco` (`idEndereco`, `idUsuario`, `cep`, `rua`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `tipoEndereco`) VALUES
(1, 2, '12289-160', 'Rua Dona Domitila de Freitas Guimarães', '16', 'Casa', 'Parque Residencial Eldorado', 'Caçapava', 'SP', 'entrega'),
(2, 2, '12283-865', 'Rua Benedito Sa de Araujo', '380', 'Casa', 'Parque Residencial Santo André', 'Caçapava', 'SP', 'entrega');

-- --------------------------------------------------------

--
-- Estrutura para tabela `formas_pagamento`
--

CREATE TABLE `formas_pagamento` (
  `idFormaPagamento` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `tipoPagamento` enum('cartao_credito','pix','boleto','transferencia') NOT NULL,
  `nomeTitular` varchar(100) DEFAULT NULL,
  `numeroCartao` varchar(255) DEFAULT NULL,
  `validadeCartao` char(5) DEFAULT NULL,
  `bandeiraCartao` varchar(50) DEFAULT NULL,
  `chavePix` varchar(255) DEFAULT NULL,
  `cpfTitular` char(14) DEFAULT NULL,
  `dataCadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `formas_pagamento`
--

INSERT INTO `formas_pagamento` (`idFormaPagamento`, `idUsuario`, `tipoPagamento`, `nomeTitular`, `numeroCartao`, `validadeCartao`, `bandeiraCartao`, `chavePix`, `cpfTitular`, `dataCadastro`) VALUES
(4, 2, 'cartao_credito', 'Matheus Quirino', '5678', '12/36', 'Mastercard', '', '', '2025-10-26 12:28:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_carrinho`
--

CREATE TABLE `item_carrinho` (
  `idItem` int(11) NOT NULL,
  `idCarrinho` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `precoUnitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_pedido`
--

CREATE TABLE `item_pedido` (
  `idItemPedido` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `precoUnitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `idPedido` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idEndereco` int(11) NOT NULL,
  `dataPedido` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','pago','enviado','concluido','cancelado') DEFAULT 'pendente',
  `total` decimal(10,2) DEFAULT NULL,
  `metodoPagamento` enum('cartao','pix','boleto') DEFAULT 'cartao'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idProduto` int(11) NOT NULL,
  `nomeProduto` varchar(100) NOT NULL,
  `tipoProduto` varchar(50) DEFAULT 'Outros',
  `linhaProduto` varchar(50) DEFAULT 'Genérico',
  `valorProduto` int(11) NOT NULL,
  `descricaoProduto` text NOT NULL,
  `tagsProduto` varchar(255) DEFAULT '',
  `imagem` varchar(255) DEFAULT NULL,
  `quantidadeProduto` int(11) NOT NULL,
  `vendasProduto` int(11) NOT NULL,
  `idAdm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idProduto`, `nomeProduto`, `tipoProduto`, `linhaProduto`, `valorProduto`, `descricaoProduto`, `tagsProduto`, `imagem`, `quantidadeProduto`, `vendasProduto`, `idAdm`) VALUES
(20, 'NVIDIA GeForce RTX 4090 24GB', 'Placa de Vídeo', 'NVIDIA', 9999, 'Placa de vídeo topo de linha com 24GB GDDR6X, ideal para jogos em 4K e edição de vídeos em alta resolução.', 'gamer,placa de vídeo,rtx,4k,desempenho,24gb,nvidia,dlss,ray tracing', '../imagens_produtos/NVIDIA GeForce RTX 4090 24GB.webp', 10, 68, 1),
(21, 'AMD Ryzen 9 7950X', 'Processador', 'AMD', 2899, 'Processador de 16 núcleos e 32 threads, com clock base de 4.5GHz, excelente para multitarefas e jogos.', 'ryzen,processador,gamer,alto desempenho,16 núcleos,amd,am5,multitarefa', '../imagens_produtos/AMD Ryzen 9 7950X.avif', 15, 40, 1),
(22, 'Corsair Vengeance LPX 32GB DDR4 3200MHz', 'Memória RAM', 'Corsair', 449, 'Kit de memória RAM de 32GB (2x16GB), ideal para jogos e edição de vídeos.', 'memória,ram,ddr4,pc,gamer,32gb,corsair,vengeance,dual channel', '../imagens_produtos/Corsair Vengeance LPX 32GB DDR4 3200MHz.jpg', 20, 96, 1),
(23, 'ASUS ROG Strix Z690-E', 'Placa-mãe', 'Intel', 1299, 'Placa-mãe com chipset Z690, suporte para DDR5, PCIe 5.0 e WiFi 6E, ideal para builds de alto desempenho.', 'placa-mãe,z690,ddr5,wifi,rog,intel,lga1700,pcie5.0,asus', '../imagens_produtos/ASUS ROG Strix Z690-E.png', 12, 58, 1),
(24, 'Samsung 970 EVO Plus 1TB', 'SSD', 'Samsung', 599, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 3500MB/s e gravação de até 3300MB/s.', 'ssd,nvme,1tb,armazenamento,rapidez,samsung,m.2,970 evo plus', '../imagens_produtos/Samsung 970 EVO Plus 1TB.jpg', 25, 4, 1),
(25, 'NVIDIA GeForce RTX 4080 16GB', 'Placa de Vídeo', 'NVIDIA', 6499, 'Placa de vídeo com 16GB GDDR6X, excelente para jogos em 4K e edição de vídeos em alta resolução.', 'gamer,placa de vídeo,rtx,4k,16gb,nvidia,dlss,ray tracing', '../imagens_produtos/NVIDIA GeForce RTX 4080 16GB.webp', 8, 45, 1),
(26, 'Intel Core i9-14900KF', 'Processador', 'Intel', 4299, 'Processador de 24 núcleos e 32 threads, com clock base de 3.5GHz, ideal para tarefas intensivas e jogos.', 'intel,processador,gamer,desempenho,24 núcleos,lga1700,14ª geração', '../imagens_produtos/Intel Core i9-14900KF.webp', 10, 13, 1),
(27, 'G.Skill Trident Z5 RGB 32GB DDR5 6000MHz', 'Memória RAM', 'G.Skill', 899, 'Kit de memória RAM de 32GB (2x16GB), com iluminação RGB e alta frequência para desempenho extremo.', 'memória,ram,ddr5,rgb,gamer,32gb,g.skill,trident,6000mhz', '../imagens_produtos/G.Skill Trident Z5 RGB 32GB DDR5 6000MHz.jpg', 18, 30, 1),
(28, 'MSI MPG Z790 Carbon WiFi', 'Placa-mãe', 'Intel', 1199, 'Placa-mãe com chipset Z790, suporte para DDR5, PCIe 5.0 e WiFi 6E, ideal para builds de alto desempenho.', 'placa-mãe,z790,ddr5,wifi,carbon,intel,msi,pcie5.0', '../imagens_produtos/MSI MPG Z790 Carbon WiFi.jpg', 14, 10, 1),
(29, 'Western Digital Black SN850X 1TB', 'SSD', 'WD', 699, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 7300MB/s e gravação de até 5300MB/s.', 'ssd,nvme,1tb,armazenamento,rápido,wd black,sn850x,m.2', '../imagens_produtos/Western Digital Black SN850X 1TB.webp', 22, 57, 1),
(30, 'NVIDIA GeForce RTX 4070 Ti 12GB', 'Placa de Vídeo', 'NVIDIA', 5299, 'Placa de vídeo com 12GB GDDR6X, excelente para jogos em 1440p e edição de vídeos em alta resolução.', 'gamer,placa de vídeo,rtx,1440p,12gb,nvidia,dlss,ray tracing', '../imagens_produtos/NVIDIA GeForce RTX 4070 Ti 12GB.webp', 9, 57, 1),
(31, 'AMD Ryzen 7 7800X', 'Processador', 'AMD', 2299, 'Processador de 8 núcleos e 16 threads, com clock base de 4.7GHz, ideal para jogos e multitarefas.', 'ryzen,processador,gamer,desempenho,8 núcleos,amd,am5', '../imagens_produtos/AMD Ryzen 7 7800X.jpg', 13, 15, 1),
(32, 'Corsair Vengeance LPX 16GB DDR4 3200MHz', 'Memória RAM', 'Corsair', 249, 'Memória RAM de 16GB (1x16GB), ideal para builds de entrada e upgrades.', 'memória,ram,ddr4,pc,upgrade,16gb,corsair,vengeance', '../imagens_produtos/Corsair Vengeance LPX 16GB DDR4 3200MHz.webp', 30, 1, 1),
(33, 'ASRock B550 Steel Legend', 'Placa-mãe', 'AMD', 899, 'Placa-mãe com chipset B550, suporte para DDR4, PCIe 4.0 e WiFi 6, ideal para builds de médio desempenho.', 'placa-mãe,b550,ddr4,wifi,steel legend,amd,am4,asrock', '../imagens_produtos/ASRock B550 Steel Legend.png', 16, 59, 1),
(34, 'Kingston A2000 1TB', 'SSD', 'Kingston', 399, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 2200MB/s e gravação de até 2000MB/s.', 'ssd,nvme,1tb,armazenamento,rápido,kingston,a2000,m.2', '../imagens_produtos/Kingston A2000 1TB.jpg', 28, 91, 1),
(35, 'NVIDIA GeForce GTX 1660 Super 6GB', 'Placa de Vídeo', 'NVIDIA', 1599, 'Placa de vídeo com 6GB GDDR5, ideal para jogos em 1080p e edição de vídeos em resolução média.', 'gamer,placa de vídeo,gtx,1080p,6gb,nvidia,entrada', '../imagens_produtos/NVIDIA GeForce GTX 1660 Super 6GB.jpg', 11, 77, 1),
(36, 'Intel Core i5-13600K', 'Processador', 'Intel', 1599, 'Processador de 14 núcleos e 20 threads, com clock base de 3.5GHz, ideal para jogos e multitarefas.', 'intel,processador,gamer,multitarefas,14 núcleos,lga1700', '../imagens_produtos/Intel Core i5-13600K.jpg', 17, 11, 1),
(37, 'Corsair Vengeance LPX 8GB DDR4 3200MHz', 'Memória RAM', 'Corsair', 149, 'Memória RAM de 8GB (1x8GB), ideal para builds de entrada e upgrades.', 'memória,ram,ddr4,pc,entrada,8gb,corsair,vengeance', '../imagens_produtos/Corsair Vengeance LPX 8GB DDR4 3200MHz.jpg', 35, 25, 1),
(38, 'ASUS TUF Gaming B550-Plus', 'Placa-mãe', 'AMD', 799, 'Placa-mãe com chipset B550, suporte para DDR4, PCIe 4.0 e WiFi 5, ideal para builds de médio desempenho.', 'placa-mãe,b550,ddr4,wifi,tuf gaming,amd,am4,asus', '../imagens_produtos/ASUS TUF Gaming B550-Plus.webp', 19, 91, 1),
(39, 'AMD Radeon RX 7900 XTX 24GB', 'Placa de Vídeo', 'AMD', 8999, 'Placa de vídeo de alta performance com 24GB GDDR6, ideal para jogos 4K e produtividade extrema.', 'gamer,placa de vídeo,rx,4k,desempenho,24gb,amd,radeon', '../imagens_produtos/AMD Radeon RX 7900 XTX 24GB.jpg', 10, 35, 1),
(40, 'Intel Core i7-13700K', 'Processador', 'Intel', 2799, 'Processador de 16 núcleos e 24 threads, com clock base de 3.4GHz, excelente para multitarefas e jogos pesados.', 'intel,processador,gamer,eficiente,16 núcleos,lga1700', '../imagens_produtos/Intel Core i7-13700K.jpg', 12, 29, 1),
(41, 'Corsair Vengeance LPX 64GB DDR4 3600MHz', 'Memória RAM', 'Corsair', 699, 'Kit de memória RAM de 64GB (2x32GB), ideal para builds de alta performance e edição de vídeos.', 'memória,ram,ddr4,64gb,edição,corsair,vengeance,3600mhz', '../imagens_produtos/Corsair Vengeance LPX 64GB DDR4 3600MHz.webp', 8, 16, 1),
(42, 'MSI GeForce RTX 3060 Ti 8GB', 'Placa de Vídeo', 'NVIDIA', 2899, 'Placa de vídeo com 8GB GDDR6, excelente para jogos em 1080p e 1440p e edição de vídeos intermediária.', 'gamer,placa de vídeo,rtx,3060,1080p,8gb,nvidia,msi', '../imagens_produtos/MSI GeForce RTX 3060 Ti 8GB.jpg', 20, 70, 1),
(43, 'Kingston Fury Beast 32GB DDR5 5600MHz', 'Memória RAM', 'Kingston', 799, 'Memória RAM de 32GB (2x16GB), com alta frequência para builds de alto desempenho.', 'memória,ram,ddr5,alto desempenho,32gb,kingston,fury beast', '../imagens_produtos/Kingston Fury Beast 32GB DDR5 5600MHz.jpg', 15, 55, 1),
(44, 'Seagate FireCuda 530 1TB', 'SSD', 'Seagate', 799, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 7300MB/s e gravação de até 6900MB/s.', 'ssd,nvme,1tb,armazenamento,rápido,seagate,firecuda,m.2', '../imagens_produtos/Seagate FireCuda 530 1TB.webp', 18, 12, 1),
(45, 'Cooler Master Hyper 212 Black Edition', 'Cooler', 'Cooler Master', 199, 'Cooler para processadores com excelente desempenho de resfriamento e design elegante.', 'cooler,processador,resfriamento,silencioso,cooler master,air cooler', '../imagens_produtos/Cooler Master Hyper 212 Black Edition.jpg', 30, 50, 1),
(46, 'ASUS TUF Gaming GeForce GTX 1650 4GB', 'Placa de Vídeo', 'NVIDIA', 1299, 'Placa de vídeo com 4GB GDDR5, ideal para jogos em 1080p e tarefas cotidianas.', 'gamer,placa de vídeo,gtx,1080p,tuf,4gb,nvidia,asus', '../imagens_produtos/ASUS TUF Gaming GeForce GTX 1650 4GB.jpg', 25, 40, 1),
(47, 'Thermaltake Toughpower GF1 850W', 'Fonte', 'Thermaltake', 599, 'Fonte de alimentação de 850W, com eficiência 80+ Gold e compatibilidade com sistemas de alta performance.', 'fonte,energia,pc,gold,850w,thermaltake,modular', '../imagens_produtos/Thermaltake Toughpower GF1 850W.jpg', 15, 22, 1),
(48, 'G.Skill Ripjaws V 16GB DDR4 3200MHz', 'Memória RAM', 'G.Skill', 349, 'Memória RAM de 16GB (2x8GB), ideal para jogos e multitarefas.', 'memória,ram,ddr4,gamer,16gb,g.skill,ripjaws,dual channel', '../imagens_produtos/G.Skill Ripjaws V 16GB DDR4 3200MHz.jpg', 28, 33, 1),
(49, 'Acer Predator Helios 300', 'Notebook', 'Intel', 7999, 'Notebook gamer com processador Intel i7, placa de vídeo RTX 3070 e tela de 15,6\" 144Hz.', 'notebook,gamer,rtx,laptop,helios,acer,intel,i7', '../imagens_produtos/Acer Predator Helios 300.jpg', 10, 14, 1),
(50, 'NZXT H510 Flow', 'Gabinete', 'NZXT', 399, 'Gabinete Mid Tower com ótimo fluxo de ar, design minimalista e lateral em vidro temperado.', 'gabinete,mid tower,nzxt,pc,gamer,airflow,vidro', '../imagens_produtos/NZXT H510 Flow.jpg', 15, 0, 1),
(51, 'Corsair iCUE 4000X RGB', 'Gabinete', 'Corsair', 549, 'Gabinete com painel de vidro temperado e três ventoinhas RGB incluídas.', 'gabinete,corsair,rgb,vidro,mid tower,4000x,icue', '../imagens_produtos/Corsair iCUE 4000X RGB.webp', 12, 0, 1),
(52, 'Cooler Master MasterBox TD500', 'Gabinete', 'Cooler Master', 449, 'Gabinete Mid Tower com excelente ventilação e design geométrico moderno.', 'gabinete,td500,pc,airflow,cooler master,mid tower', '../imagens_produtos/Cooler Master MasterBox TD500.webp', 10, 0, 1),
(53, 'Lian Li Lancool 216', 'Gabinete', 'Lian Li', 599, 'Gabinete espaçoso com excelente fluxo de ar e suporte a placas de vídeo grandes.', 'gabinete,lian li,mid tower,airflow,lancool,216', '../imagens_produtos/Lian Li Lancool 216.webp', 8, 0, 1),
(54, 'Thermaltake Versa H21', 'Gabinete', 'Thermaltake', 299, 'Gabinete de entrada com design limpo e estrutura resistente.', 'gabinete,thermaltake,entrada,pc,mid tower,versa', '../imagens_produtos/Thermaltake Versa H21.jpg', 18, 0, 1),
(55, 'ASUS TUF Gaming GT501', 'Gabinete', 'ASUS', 699, 'Gabinete robusto com visual gamer, excelente fluxo de ar e filtros magnéticos.', 'gabinete,asus,tuf,gamer,mid tower,gt501', '../imagens_produtos/ASUS TUF Gaming GT501.png', 7, 0, 1),
(56, 'Windows 11 Home (Licença Digital)', 'Sistema Operacional', 'Microsoft', 299, 'Licença original do Windows 11 Home, ativação online e suporte oficial.', 'sistema,windows,microsoft,licença,11,home,digital', '../imagens_produtos/Windows 11 Home.avif', 50, 0, 1),
(57, 'Windows 11 Pro (Licença Digital)', 'Sistema Operacional', 'Microsoft', 499, 'Licença original do Windows 11 Pro, ideal para usuários avançados e empresas.', 'sistema,windows,pro,licença,11,microsoft,digital,empresarial', '../imagens_produtos/Windows 11 Pro.avif', 50, 0, 1),
(58, 'Ubuntu 24.04 LTS (Instalação)', 'Sistema Operacional', 'Canonical', 0, 'Instalação gratuita do Ubuntu Linux com configuração básica.', 'sistema,linux,ubuntu,grátis,lts,opensource,canonical', '../imagens_produtos/Ubuntu 24.04 LTS.png', 999, 0, 1),
(59, 'Windows 10 Home (Licença Digital)', 'Sistema Operacional', 'Microsoft', 249, 'Licença original do Windows 10 Home, ativação online e suporte Microsoft.', 'sistema,windows10,microsoft,licença,home,digital', '../imagens_produtos/Windows 10 Home.webp', 30, 0, 1),
(60, 'Windows 10 Pro (Licença Digital)', 'Sistema Operacional', 'Microsoft', 399, 'Licença original do Windows 10 Pro, ideal para uso corporativo.', 'sistema,windows,pro,microsoft,10,licença,digital,empresarial', '../imagens_produtos/Windows 10 Pro.webp', 30, 0, 1),
(61, 'Linux Mint 22 (Instalação)', 'Sistema Operacional', 'Linux Mint', 0, 'Instalação gratuita do Linux Mint com interface leve e amigável.', 'sistema,linux,mint,grátis,opensource,ubuntu-based', '../imagens_produtos/Linux Mint 22.jpg', 999, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico_montagem`
--

CREATE TABLE `servico_montagem` (
  `idMontagem` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `nomeSetup` varchar(100) DEFAULT NULL,
  `cpu` varchar(100) DEFAULT NULL,
  `gpu` varchar(100) DEFAULT NULL,
  `placaMae` varchar(100) DEFAULT NULL,
  `ram` varchar(100) DEFAULT NULL,
  `armazenamento` varchar(100) DEFAULT NULL,
  `fonte` varchar(100) DEFAULT NULL,
  `gabinete` varchar(100) DEFAULT NULL,
  `cooler` varchar(100) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `precoEstimado` decimal(10,2) DEFAULT NULL,
  `dataSolicitacao` datetime DEFAULT current_timestamp(),
  `status` enum('em análise','em montagem','finalizado') DEFAULT 'em análise'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nomeUsuario` varchar(50) NOT NULL,
  `sobrenomeUsuario` varchar(150) NOT NULL,
  `cpfUsuario` varchar(14) DEFAULT NULL,
  `celularUsuario` varchar(14) NOT NULL,
  `emailUsuario` varchar(255) NOT NULL,
  `senhaUsuario` varchar(255) NOT NULL,
  `nascimentoUsuario` date NOT NULL,
  `fotoUsuario` varchar(255) DEFAULT NULL,
  `dataCadastro` datetime DEFAULT current_timestamp(),
  `tipoUsuario` enum('cliente','admin') DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nomeUsuario`, `sobrenomeUsuario`, `cpfUsuario`, `celularUsuario`, `emailUsuario`, `senhaUsuario`, `nascimentoUsuario`, `fotoUsuario`, `dataCadastro`, `tipoUsuario`) VALUES
(2, 'Matheus', 'Quirino', NULL, '(12) 991177672', 'quirinojulio77@gmail.com', '$2y$10$FNooLCvVYstKa1WX3RiHYeQpy84tIoWj7CTH79dWKy2SueGisM7Xa', '2000-08-11', '../ImagensUsuarios/2.jpg', '2025-10-26 00:46:42', 'cliente'),
(3, 'Marcelo', 'Quirino', NULL, '(12) 99117-767', 'marcelao@gmail.com', '$2y$10$Ju9/.9tOkIxY5y6Rufr.N.Br5tN4N1gnKNygjSAVMwgpWfilcIspe', '0000-00-00', NULL, '2025-10-26 00:46:42', 'cliente');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`idAdm`);

--
-- Índices de tabela `avaliacao_produto`
--
ALTER TABLE `avaliacao_produto`
  ADD PRIMARY KEY (`idAvaliacao`),
  ADD KEY `idProduto` (`idProduto`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`idCarrinho`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lido` (`lido`),
  ADD KEY `idx_data` (`data_envio`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`idEndereco`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Índices de tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  ADD PRIMARY KEY (`idFormaPagamento`),
  ADD KEY `fk_pagamento_usuario` (`idUsuario`);

--
-- Índices de tabela `item_carrinho`
--
ALTER TABLE `item_carrinho`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `idCarrinho` (`idCarrinho`),
  ADD KEY `idProduto` (`idProduto`);

--
-- Índices de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`idItemPedido`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idProduto` (`idProduto`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idEndereco` (`idEndereco`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idProduto`),
  ADD KEY `fk_produto_adm` (`idAdm`);

--
-- Índices de tabela `servico_montagem`
--
ALTER TABLE `servico_montagem`
  ADD PRIMARY KEY (`idMontagem`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `idAdm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `avaliacao_produto`
--
ALTER TABLE `avaliacao_produto`
  MODIFY `idAvaliacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `idCarrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `idEndereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  MODIFY `idFormaPagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `item_carrinho`
--
ALTER TABLE `item_carrinho`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `idItemPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `servico_montagem`
--
ALTER TABLE `servico_montagem`
  MODIFY `idMontagem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacao_produto`
--
ALTER TABLE `avaliacao_produto`
  ADD CONSTRAINT `avaliacao_produto_ibfk_1` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`),
  ADD CONSTRAINT `avaliacao_produto_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`);

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `endereco`
--
ALTER TABLE `endereco`
  ADD CONSTRAINT `endereco_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  ADD CONSTRAINT `fk_pagamento_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `item_carrinho`
--
ALTER TABLE `item_carrinho`
  ADD CONSTRAINT `item_carrinho_ibfk_1` FOREIGN KEY (`idCarrinho`) REFERENCES `carrinho` (`idCarrinho`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_carrinho_ibfk_2` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`);

--
-- Restrições para tabelas `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `item_pedido_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_pedido_ibfk_2` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`);

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`idEndereco`) REFERENCES `endereco` (`idEndereco`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produto_adm` FOREIGN KEY (`idAdm`) REFERENCES `administrador` (`idAdm`) ON DELETE SET NULL;

--
-- Restrições para tabelas `servico_montagem`
--
ALTER TABLE `servico_montagem`
  ADD CONSTRAINT `servico_montagem_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

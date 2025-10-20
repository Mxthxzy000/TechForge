-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/10/2025 às 03:07
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
(20, 'NVIDIA GeForce RTX 4090 24GB', 'Placa de Vídeo', 'NVIDIA', 14999, 'Placa de vídeo topo de linha com 24GB GDDR6X, ideal para jogos em 4K e edição de vídeos em alta resolução.', 'gamer,placa de vídeo,rtx,4k,desempenho', '../imagens_produtos/NVIDIA GeForce RTX 4090 24GB.webp', 10, 68, 1),
(21, 'AMD Ryzen 9 7950X', 'Processador', 'AMD', 4999, 'Processador de 16 núcleos e 32 threads, com clock base de 4.5GHz, excelente para multitarefas e jogos.', 'ryzen,processador,gamer,alto desempenho', '../imagens_produtos/AMD Ryzen 9 7950X.avif', 15, 40, 1),
(22, 'Corsair Vengeance LPX 32GB DDR4 3200MHz', 'Memória RAM', 'Corsair', 899, 'Kit de memória RAM de 32GB (2x16GB), ideal para jogos e edição de vídeos.', 'memória,ram,ddr4,pc,gamer', '../imagens_produtos/Corsair Vengeance LPX 32GB DDR4 3200MHz.jpg', 20, 96, 1),
(23, 'ASUS ROG Strix Z690-E', 'Placa-mãe', 'Intel', 1999, 'Placa-mãe com chipset Z690, suporte para DDR5, PCIe 5.0 e WiFi 6E, ideal para builds de alto desempenho.', 'placa-mãe,z690,ddr5,wifi,rog', '../imagens_produtos/ASUS ROG Strix Z690-E.png', 12, 58, 1),
(24, 'Samsung 970 EVO Plus 1TB', 'SSD', 'Samsung', 799, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 3500MB/s e gravação de até 3300MB/s.', 'ssd,nvme,1tb,armazenamento,rapidez', '../imagens_produtos/Samsung 970 EVO Plus 1TB.jpg', 25, 4, 1),
(25, 'NVIDIA GeForce RTX 4080 16GB', 'Placa de Vídeo', 'NVIDIA', 11999, 'Placa de vídeo com 16GB GDDR6X, excelente para jogos em 4K e edição de vídeos em alta resolução.', 'gamer,placa de vídeo,rtx,4k', '../imagens_produtos/NVIDIA GeForce RTX 4080 16GB.webp', 8, 45, 1),
(26, 'Intel Core i9-14900KF', 'Processador', 'Intel', 5999, 'Processador de 24 núcleos e 32 threads, com clock base de 3.5GHz, ideal para tarefas intensivas e jogos.', 'intel,processador,gamer,desempenho', '../imagens_produtos/Intel Core i9-14900KF.webp', 10, 13, 1),
(27, 'G.Skill Trident Z5 RGB 32GB DDR5 6000MHz', 'Memória RAM', 'G.Skill', 1299, 'Kit de memória RAM de 32GB (2x16GB), com iluminação RGB e alta frequência para desempenho extremo.', 'memória,ram,ddr5,rgb,gamer', '../imagens_produtos/G.Skill Trident Z5 RGB 32GB DDR5 6000MHz.jpg', 18, 30, 1),
(28, 'MSI MPG Z790 Carbon WiFi', 'Placa-mãe', 'Intel', 1799, 'Placa-mãe com chipset Z790, suporte para DDR5, PCIe 5.0 e WiFi 6E, ideal para builds de alto desempenho.', 'placa-mãe,z790,ddr5,wifi,carbon', '../imagens_produtos/MSI MPG Z790 Carbon WiFi.jpg', 14, 10, 1),
(29, 'Western Digital Black SN850X 1TB', 'SSD', 'WD', 899, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 7300MB/s e gravação de até 5300MB/s.', 'ssd,nvme,1tb,armazenamento,rápido', '../imagens_produtos/Western Digital Black SN850X 1TB.webp', 22, 57, 1),
(30, 'NVIDIA GeForce RTX 4070 Ti 12GB', 'Placa de Vídeo', 'NVIDIA', 8999, 'Placa de vídeo com 12GB GDDR6X, excelente para jogos em 1440p e edição de vídeos em alta resolução.', 'gamer,placa de vídeo,rtx,1440p', '../imagens_produtos/NVIDIA GeForce RTX 4070 Ti 12GB.webp', 9, 57, 1),
(31, 'AMD Ryzen 7 7800X', 'Processador', 'AMD', 3999, 'Processador de 8 núcleos e 16 threads, com clock base de 4.7GHz, ideal para jogos e multitarefas.', 'ryzen,processador,gamer,desempenho', '../imagens_produtos/AMD Ryzen 7 7800X.jpg', 13, 15, 1),
(32, 'Corsair Vengeance LPX 16GB DDR4 3200MHz', 'Memória RAM', 'Corsair', 499, 'Memória RAM de 16GB (1x16GB), ideal para builds de entrada e upgrades.', 'memória,ram,ddr4,pc,upgrade', '../imagens_produtos/Corsair Vengeance LPX 16GB DDR4 3200MHz.webp', 30, 1, 1),
(33, 'ASRock B550 Steel Legend', 'Placa-mãe', 'AMD', 799, 'Placa-mãe com chipset B550, suporte para DDR4, PCIe 4.0 e WiFi 6, ideal para builds de médio desempenho.', 'placa-mãe,b550,ddr4,wifi,steel legend', '../imagens_produtos/ASRock B550 Steel Legend.png', 16, 59, 1),
(34, 'Kingston A2000 1TB', 'SSD', 'Kingston', 649, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 2200MB/s e gravação de até 2000MB/s.', 'ssd,nvme,1tb,armazenamento,rápido', '../imagens_produtos/Kingston A2000 1TB.jpg', 28, 91, 1),
(35, 'NVIDIA GeForce GTX 1660 Super 6GB', 'Placa de Vídeo', 'NVIDIA', 2999, 'Placa de vídeo com 6GB GDDR5, ideal para jogos em 1080p e edição de vídeos em resolução média.', 'gamer,placa de vídeo,gtx,1080p', '../imagens_produtos/NVIDIA GeForce GTX 1660 Super 6GB.jpg', 11, 77, 1),
(36, 'Intel Core i5-13600K', 'Processador', 'Intel', 2499, 'Processador de 14 núcleos e 20 threads, com clock base de 3.5GHz, ideal para jogos e multitarefas.', 'intel,processador,gamer,multitarefas', '../imagens_produtos/Intel Core i5-13600K.jpg', 17, 11, 1),
(37, 'Corsair Vengeance LPX 8GB DDR4 3200MHz', 'Memória RAM', 'Corsair', 249, 'Memória RAM de 8GB (1x8GB), ideal para builds de entrada e upgrades.', 'memória,ram,ddr4,pc,entrada', '../imagens_produtos/Corsair Vengeance LPX 8GB DDR4 3200MHz.jpg', 35, 25, 1),
(38, 'ASUS TUF Gaming B550-Plus', 'Placa-mãe', 'AMD', 699, 'Placa-mãe com chipset B550, suporte para DDR4, PCIe 4.0 e WiFi 5, ideal para builds de médio desempenho.', 'placa-mãe,b550,ddr4,wifi,tuf gaming', '../imagens_produtos/ASUS TUF Gaming B550-Plus.webp', 19, 91, 1),
(39, 'AMD Radeon RX 7900 XTX 24GB', 'Placa de Vídeo', 'AMD', 15999, 'Placa de vídeo de alta performance com 24GB GDDR6, ideal para jogos 4K e produtividade extrema.', 'gamer,placa de vídeo,rx,4k,desempenho', '../imagens_produtos/AMD Radeon RX 7900 XTX 24GB.jpg', 10, 35, 1),
(40, 'Intel Core i7-13700K', 'Processador', 'Intel', 3799, 'Processador de 16 núcleos e 24 threads, com clock base de 3.4GHz, excelente para multitarefas e jogos pesados.', 'intel,processador,gamer,eficiente', '../imagens_produtos/Intel Core i7-13700K.jpg', 12, 29, 1),
(41, 'Corsair Vengeance LPX 64GB DDR4 3600MHz', 'Memória RAM', 'Corsair', 1799, 'Kit de memória RAM de 64GB (2x32GB), ideal para builds de alta performance e edição de vídeos.', 'memória,ram,ddr4,64gb,edição', '../imagens_produtos/Corsair Vengeance LPX 64GB DDR4 3600MHz.webp', 8, 16, 1),
(42, 'MSI GeForce RTX 3060 Ti 8GB', 'Placa de Vídeo', 'NVIDIA', 4999, 'Placa de vídeo com 8GB GDDR6, excelente para jogos em 1080p e 1440p e edição de vídeos intermediária.', 'gamer,placa de vídeo,rtx,3060,1080p', '../imagens_produtos/MSI GeForce RTX 3060 Ti 8GB.jpg', 20, 70, 1),
(43, 'Kingston Fury Beast 32GB DDR5 5600MHz', 'Memória RAM', 'Kingston', 1399, 'Memória RAM de 32GB (2x16GB), com alta frequência para builds de alto desempenho.', 'memória,ram,ddr5,alto desempenho', '../imagens_produtos/Kingston Fury Beast 32GB DDR5 5600MHz.jpg', 15, 55, 1),
(44, 'Seagate FireCuda 530 1TB', 'SSD', 'Seagate', 1199, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 7300MB/s e gravação de até 6900MB/s.', 'ssd,nvme,1tb,armazenamento,rápido', '../imagens_produtos/Seagate FireCuda 530 1TB.webp', 18, 12, 1),
(45, 'Cooler Master Hyper 212 Black Edition', 'Cooler', 'Cooler Master', 299, 'Cooler para processadores com excelente desempenho de resfriamento e design elegante.', 'cooler,processador,resfriamento,silencioso', '../imagens_produtos/Cooler Master Hyper 212 Black Edition.jpg', 30, 50, 1),
(46, 'ASUS TUF Gaming GeForce GTX 1650 4GB', 'Placa de Vídeo', 'NVIDIA', 1999, 'Placa de vídeo com 4GB GDDR5, ideal para jogos em 1080p e tarefas cotidianas.', 'gamer,placa de vídeo,gtx,1080p,tuf', '../imagens_produtos/ASUS TUF Gaming GeForce GTX 1650 4GB.jpg', 25, 40, 1),
(47, 'Thermaltake Toughpower GF1 850W', 'Fonte', 'Thermaltake', 799, 'Fonte de alimentação de 850W, com eficiência 80+ Gold e compatibilidade com sistemas de alta performance.', 'fonte,energia,pc,gold', '../imagens_produtos/Thermaltake Toughpower GF1 850W.jpg', 15, 22, 1),
(48, 'G.Skill Ripjaws V 16GB DDR4 3200MHz', 'Memória RAM', 'G.Skill', 699, 'Memória RAM de 16GB (2x8GB), ideal para jogos e multitarefas.', 'memória,ram,ddr4,gamer', '../imagens_produtos/G.Skill Ripjaws V 16GB DDR4 3200MHz.jpg', 28, 33, 1),
(49, 'Acer Predator Helios 300', 'Notebook', 'Intel', 12499, 'Notebook gamer com processador Intel i7, placa de vídeo RTX 3070 e tela de 15,6\" 144Hz.', 'notebook,gamer,rtx,laptop,helios', '../imagens_produtos/Acer Predator Helios 300.jpg', 10, 14, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nomeUsuario` varchar(50) NOT NULL,
  `sobrenomeUsuario` varchar(150) NOT NULL,
  `celularUsuario` varchar(14) NOT NULL,
  `emailUsuario` varchar(255) NOT NULL,
  `senhaUsuario` varchar(255) NOT NULL,
  `nascimentoUsuario` date NOT NULL,
  `fotoUsuario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nomeUsuario`, `sobrenomeUsuario`, `celularUsuario`, `emailUsuario`, `senhaUsuario`, `nascimentoUsuario`, `fotoUsuario`) VALUES
(2, 'Matheus', 'Quirino', '(12) 99117-767', 'quirinojulio77@gmail.com', '$2y$10$FNooLCvVYstKa1WX3RiHYeQpy84tIoWj7CTH79dWKy2SueGisM7Xa', '0000-00-00', NULL),
(3, 'Marcelo', 'Quirino', '(12) 99117-767', 'marcelao@gmail.com', '$2y$10$Ju9/.9tOkIxY5y6Rufr.N.Br5tN4N1gnKNygjSAVMwgpWfilcIspe', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`idAdm`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lido` (`lido`),
  ADD KEY `idx_data` (`data_envio`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idProduto`),
  ADD KEY `fk_produto_adm` (`idAdm`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `idAdm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produto_adm` FOREIGN KEY (`idAdm`) REFERENCES `administrador` (`idAdm`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

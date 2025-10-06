-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/10/2025 às 04:32
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
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idProduto` int(11) NOT NULL,
  `nomeProduto` varchar(100) NOT NULL,
  `valorProduto` int(11) NOT NULL,
  `descricaoProduto` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `quantidadeProduto` int(11) NOT NULL,
  `vendasProduto` int(11) NOT NULL,
  `idAdm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idProduto`, `nomeProduto`, `valorProduto`, `descricaoProduto`, `imagem`, `quantidadeProduto`, `vendasProduto`, `idAdm`) VALUES
(20, 'NVIDIA GeForce RTX 4090 24GB', 14999, 'Placa de vídeo topo de linha com 24GB GDDR6X, ideal para jogos em 4K e edição de vídeos em alta resolução.', '../imagens_produtos/NVIDIA GeForce RTX 4090 24GB.webp', 10, 68, 1),
(21, 'AMD Ryzen 9 7950X', 4999, 'Processador de 16 núcleos e 32 threads, com clock base de 4.5GHz, excelente para multitarefas e jogos.', '../imagens_produtos/AMD Ryzen 9 7950X.avif', 15, 40, 1),
(22, 'Corsair Vengeance LPX 32GB DDR4 3200MHz', 899, 'Kit de memória RAM de 32GB (2x16GB), ideal para jogos e edição de vídeos.', '../imagens_produtos/Corsair Vengeance LPX 32GB DDR4 3200MHz.jpg', 20, 96, 1),
(23, 'ASUS ROG Strix Z690-E', 1999, 'Placa-mãe com chipset Z690, suporte para DDR5, PCIe 5.0 e WiFi 6E, ideal para builds de alto desempenho.', '../imagens_produtos/ASUS ROG Strix Z690-E.png', 12, 58, 1),
(24, 'Samsung 970 EVO Plus 1TB', 799, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 3500MB/s e gravação de até 3300MB/s.', '../imagens_produtos/Samsung 970 EVO Plus 1TB.jpg', 25, 4, 1),
(25, 'NVIDIA GeForce RTX 4080 16GB', 11999, 'Placa de vídeo com 16GB GDDR6X, excelente para jogos em 4K e edição de vídeos em alta resolução.', '../imagens_produtos/NVIDIA GeForce RTX 4080 16GB.webp', 8, 45, 1),
(26, 'Intel Core i9-14900KF', 5999, 'Processador de 24 núcleos e 32 threads, com clock base de 3.5GHz, ideal para tarefas intensivas e jogos.', '../imagens_produtos/Intel Core i9-14900KF.webp', 10, 13, 1),
(27, 'G.Skill Trident Z5 RGB 32GB DDR5 6000MHz', 1299, 'Kit de memória RAM de 32GB (2x16GB), com iluminação RGB e alta frequência para desempenho extremo.', '../imagens_produtos/G.Skill Trident Z5 RGB 32GB DDR5 6000MHz.jpg', 18, 30, 1),
(28, 'MSI MPG Z790 Carbon WiFi', 1799, 'Placa-mãe com chipset Z790, suporte para DDR5, PCIe 5.0 e WiFi 6E, ideal para builds de alto desempenho.', '../imagens_produtos/MSI MPG Z790 Carbon WiFi.jpg', 14, 10, 1),
(29, 'Western Digital Black SN850X 1TB', 899, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 7300MB/s e gravação de até 5300MB/s.', '../imagens_produtos/Western Digital Black SN850X 1TB.webp', 22, 57, 1),
(30, 'NVIDIA GeForce RTX 4070 Ti 12GB', 8999, 'Placa de vídeo com 12GB GDDR6X, excelente para jogos em 1440p e edição de vídeos em alta resolução.', '../imagens_produtos/NVIDIA GeForce RTX 4070 Ti 12GB.webp', 9, 57, 1),
(31, 'AMD Ryzen 7 7800X', 3999, 'Processador de 8 núcleos e 16 threads, com clock base de 4.7GHz, ideal para jogos e multitarefas.', '../imagens_produtos/AMD Ryzen 7 7800X.jpg', 13, 15, 1),
(32, 'Corsair Vengeance LPX 16GB DDR4 3200MHz', 499, 'Memória RAM de 16GB (1x16GB), ideal para builds de entrada e upgrades.', '../imagens_produtos/Corsair Vengeance LPX 16GB DDR4 3200MHz.webp', 30, 1, 1),
(33, 'ASRock B550 Steel Legend', 799, 'Placa-mãe com chipset B550, suporte para DDR4, PCIe 4.0 e WiFi 6, ideal para builds de médio desempenho.', '../imagens_produtos/ASRock B550 Steel Legend.png', 16, 59, 1),
(34, 'Kingston A2000 1TB', 649, 'SSD NVMe M.2 com 1TB de capacidade, leitura de até 2200MB/s e gravação de até 2000MB/s.', '../imagens_produtos/Kingston A2000 1TB.jpg', 28, 91, 1),
(35, 'NVIDIA GeForce GTX 1660 Super 6GB', 2999, 'Placa de vídeo com 6GB GDDR5, ideal para jogos em 1080p e edição de vídeos em resolução média.', '../imagens_produtos/NVIDIA GeForce GTX 1660 Super 6GB.jpg', 11, 77, 1),
(36, 'Intel Core i5-13600K', 2499, 'Processador de 14 núcleos e 20 threads, com clock base de 3.5GHz, ideal para jogos e multitarefas.', '../imagens_produtos/Intel Core i5-13600K.jpg', 17, 11, 1),
(37, 'Corsair Vengeance LPX 8GB DDR4 3200MHz', 249, 'Memória RAM de 8GB (1x8GB), ideal para builds de entrada e upgrades.', '../imagens_produtos/Corsair Vengeance LPX 8GB DDR4 3200MHz.jpg', 35, 25, 1),
(38, 'ASUS TUF Gaming B550-Plus', 699, 'Placa-mãe com chipset B550, suporte para DDR4, PCIe 4.0 e WiFi 5, ideal para builds de médio desempenho.', '../imagens_produtos/ASUS TUF Gaming B550-Plus.webp', 19, 91, 1);

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
  `nascimentoUsuario` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`idAdm`);

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
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `idAdm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT;

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

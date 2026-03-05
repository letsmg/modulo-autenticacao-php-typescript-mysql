-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/03/2026 às 15:16
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
-- Banco de dados: `ts`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `texto` text NOT NULL,
  `data_envio` datetime NOT NULL,
  `lida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `id_remetente`, `id_destinatario`, `texto`, `data_envio`, `lida`) VALUES
(1, 1, 59, 'asd', '2026-03-05 11:10:07', 0),
(2, 1, 53, 'asdf', '2026-03-05 11:10:09', 0),
(3, 1, 58, 'asdf', '2026-03-05 11:10:11', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = padrão, 1 = admin',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ultimo_login` datetime DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1 COMMENT '1 = ativo, 0 = inativo/bloqueado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel_acesso`, `criado_em`, `atualizado_em`, `ultimo_login`, `ativo`) VALUES
(1, 'admin', 'a@a.com', '$2y$10$2FlVu4dEJaxIuQMVQefwl.gjqpGQFvsA6J.BDet7IIvVaz9uHbHoy', 1, '2026-03-03 09:06:21', '2026-03-05 11:09:48', '2026-03-05 11:09:48', 1),
(53, '32c5vic9', 'teste175@email.com', '$2y$10$Ganr2Far0S6ZdRPL3vr4XefX1aJbvS2VQdYLg2DFBWRHnLhnMYdDy', 1, '2026-03-04 10:45:16', '2026-03-04 10:45:16', NULL, 1),
(58, 'oexyfc8w', 'teste103@email.com', '$2y$10$wsWcszdDj89Q4YL8QEjlCOYhYyQst0EyWaln9MpRoA0lXEJzqAt/e', 1, '2026-03-04 10:59:12', '2026-03-04 10:59:12', NULL, 1),
(59, '09xxurlw', 'teste655@email.com', '$2y$10$BzF6oUTzkOTD0P7zcYJDCOHQUrmXIifYpuVLhoVWOlYTMyAa8TA2K', 0, '2026-03-04 10:59:17', '2026-03-04 10:59:17', NULL, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remetente` (`id_remetente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `fk_destinatario` FOREIGN KEY (`id_destinatario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_remetente` FOREIGN KEY (`id_remetente`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/03/2026 às 12:17
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
  `remetente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0 COMMENT '0 = não lida, 1 = lida'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'admin', 'a@a.com', '$2y$10$2FlVu4dEJaxIuQMVQefwl.gjqpGQFvsA6J.BDet7IIvVaz9uHbHoy', 1, '2026-03-03 09:06:21', '2026-03-04 07:40:04', '2026-03-04 07:40:04', 1),
(24, 'rf87lbps', 'teste784@email.com', '$2y$10$czPRukYWTAUfWog0wgHd/ePAq.j.dXD7a/m1Ea2DyovJNss/apsI2', 1, '2026-03-03 18:11:33', '2026-03-04 07:51:24', NULL, 1),
(25, 'dc191qm3', 'teste695@email.com', '$2y$10$XyT6KhYbUNmN7Bv7c3TfFeKf0w36YLD5SgVjm8LxwOWjKHZ/mOS0.', 0, '2026-03-03 18:12:10', '2026-03-03 18:12:10', NULL, 1),
(27, 'tck1x8kl', 'teste863@email.com', '$2y$10$S/3d1u3nS7oWZK99zIYfAOIQ9fktWLvyjp61.4q1kusQqlY8a5Zfa', 0, '2026-03-03 18:12:18', '2026-03-03 18:12:18', NULL, 1),
(28, '6sijjlc3', 'teste230@email.com', '$2y$10$.n89OMbzAQWJtuF2TfYDsuV9N6/0yBouoqoyyh6K4DlxFQnsLdyk.', 0, '2026-03-03 18:13:19', '2026-03-03 18:13:19', NULL, 1),
(29, 'vn39o2yn', 'teste813@email.com', '$2y$10$IZleUQSz.reQXkWUudjLven0bXe3EbW3IIEjAXiYnf07.Y.7dPzb2', 0, '2026-03-04 07:38:11', '2026-03-04 07:38:11', NULL, 1),
(30, 'yk8zyfd4', 'teste961@email.com', '$2y$10$2FlVu4dEJaxIuQMVQefwl.gjqpGQFvsA6J.BDet7IIvVaz9uHbHoy', 0, '2026-03-04 07:39:42', '2026-03-04 07:39:42', NULL, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_remetente` (`remetente_id`,`data_envio`),
  ADD KEY `idx_destinatario` (`destinatario_id`,`data_envio`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`remetente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

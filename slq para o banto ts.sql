SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. APAGAR TABELAS SE EXISTIREM (Ordem inversa para não dar erro de FK)
DROP TABLE IF EXISTS `mensagens`;
DROP TABLE IF EXISTS `usuarios`;

-- 2. CRIAR TABELA DE USUÁRIOS primeiro
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = padrão, 1 = admin',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ultimo_login` datetime DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1 COMMENT '1 = ativo, 0 = inativo/bloqueado',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. CRIAR TABELA DE MENSAGENS (Depende da 'usuarios')
CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `texto` text NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_remetente` (`id_remetente`),
  KEY `idx_destinatario` (`id_destinatario`),
  -- Configuração do CASCADE
  CONSTRAINT `fk_mens_remetente` FOREIGN KEY (`id_remetente`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_mens_destinatario` FOREIGN KEY (`id_destinatario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. INSERIR DADOS INICIAIS
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel_acesso`) VALUES
(1, 'admin', 'a@a.com', '$2y$10$2FlVu4dEJaxIuQMVQefwl.gjqpGQFvsA6J.BDet7IIvVaz9uHbHoy', 1),
(2, 'Usuario Dois', '2@2.com', '$2y$10$2FlVu4dEJaxIuQMVQefwl.gjqpGQFvsA6J.BDet7IIvVaz9uHbHoy', 0),
(3, 'Usuario Três', '3@3.com', '$2y$10$2FlVu4dEJaxIuQMVQefwl.gjqpGQFvsA6J.BDet7IIvVaz9uHbHoy', 0),
(4, 'Usuario Quatro', '4@4.com', '$2y$10$2FlVu4dEJaxIuQMVQefwl.gjqpGQFvsA6J.BDet7IIvVaz9uHbHoy', 1);

COMMIT;
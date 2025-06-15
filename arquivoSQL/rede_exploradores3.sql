-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/06/2025 às 16:31
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
-- Banco de dados: `rede_exploradores`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `especies`
--

CREATE TABLE `especies` (
  `especie` varchar(100) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `reino` varchar(50) DEFAULT NULL,
  `familia` varchar(50) DEFAULT NULL,
  `especialista_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `especies`
--

INSERT INTO `especies` (`especie`, `post_id`, `genero`, `reino`, `familia`, `especialista_id`) VALUES
('L.wiedii', 16, 'Leopardus', 'Animalia', 'Felídeos', 7),
('Salvator duseni', 19, 'Salvator', 'Animalia', 'Teiidae', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `usuario_id`, `criado_em`) VALUES
(36, 16, 4, '2025-06-05 15:44:19'),
(37, 16, 7, '2025-06-15 11:11:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `editado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`id`, `usuario_id`, `titulo`, `conteudo`, `imagem`, `criado_em`, `editado_em`) VALUES
(16, 4, 'Gato maracujá', 'Esse animal é uma jaguatirica ou um gato maracujá?', 'uploads/posts/post_6841dc5f9f5fc.jpg', '2025-06-05 15:05:19', NULL),
(17, 7, 'Furão ou Guaxinim?', 'Encontrei esse animal enquanto fazia trilha. \r\nEle é um furão ou guaxinim?', 'uploads/posts/post_684ed4cbbf824.jpg', '2025-06-15 11:12:27', NULL),
(18, 6, 'Ajuda para identificar primata', 'Alguém consegue me ajudar a identificar esse primata? Encontrei-o perto da casa da minha avó.', 'uploads/posts/post_684ed59dcfe58.jpeg', '2025-06-15 11:15:57', NULL),
(19, 9, 'Espécie de teiú', 'A qual espécie esse teiú pertence? Conheço o teiú-branco, teiú-vermelho, teiú-palustre, mas não se parece exatamente com eles.', 'uploads/posts/post_684ed6946d335.jpg', '2025-06-15 11:20:04', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`, `tipo`) VALUES
(1, 'teste', 'teste@teste.teste', '$2y$10$dkoHaKr3KcgWX.POK9J2IeKPUdiO4gl8pvLATNCqaBV4JoWidRh5S', NULL),
(2, 'teste1', 'teste1@teste.teste', '$2y$10$B/xL.aapJIy6aXY1TF4lmOzXKUxHPwPKUwEA5waR/b.VhFZPoFLSS', NULL),
(3, 'teste2', 'teste2@teste.teste', '$2y$10$m09TqlwfxUJ3jYXyYie83u4VwRpibOife.UtDftz2eLol4EUOIboq', NULL),
(4, 'paulo', 'paulotolotti3@gmail.com', '$2y$10$VYaOxDGe8aWgf8KIRI.45uIX8tDX7oWZda6ekvQHKTM3aNe.Bj3ei', NULL),
(5, 'Paulo', 'paulohtolotti@alumni.usp.br', '$2y$10$mz3egC5.f.FUegW.4EBlxOUMakqDbkAFz8p9WOwTy/E1I6OZnL3i.', NULL),
(6, 'Jason Blood', 'etrigan@dc.com', '$2y$10$NriuTapdMA6Gv8KAnyLGTOUNUpePq81b/UHCdYjStCvnBeubdXR9m', 'especialista'),
(7, 'Jason Todd', 'todd@dc.com', '$2y$10$sBIVYK4bXyE0Bedb2uA9/uv2E2q2Lv4rCQursPEkCiz0pzTVXMmVy', 'especialista'),
(8, 'Dr Alves', 'dralves@labs.com', '$2y$10$J3HR9T7.TFxUOT2iMBSgAO0kGvgY4LSKh6bW6xdhuD5oukEpuA6p.', 'especialista'),
(9, 'Curt Connors', 'connors@oscorp.eua', '$2y$10$B3AIPtPUixOrpvg5HRIVdetfCk6ZuyQCt7e5sG3AZwdzqW9OS/CJq', 'especialista');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `comentarios_ibfk_1` (`post_id`);

--
-- Índices de tabela `especies`
--
ALTER TABLE `especies`
  ADD PRIMARY KEY (`especie`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `especialista_id` (`especialista_id`);

--
-- Índices de tabela `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`usuario_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `especies`
--
ALTER TABLE `especies`
  ADD CONSTRAINT `especies_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `especies_ibfk_2` FOREIGN KEY (`especialista_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

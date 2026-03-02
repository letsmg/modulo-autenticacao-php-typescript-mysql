# Módulo de Autenticação PHP + TypeScript + MySQL

![Badge em Desenvolvimento](http://img.shields.io/static/v1?label=STATUS&message=EM%20DESENVOLVIMENTO&color=ORANGE&style=for-the-badge)
![Badge Licença](http://img.shields.io/static/v1?label=LICENÇA&message=MIT&color=green&style=for-the-badge)
![Badge Tecnologias](http://img.shields.io/static/v1?label=TECNOLOGIAS&message=PHP%20%7C%20MySQL%20%7C%20TypeScript%20%7C%20Vite&color=blue&style=for-the-badge)

## Descrição

Este projeto é um **módulo de autenticação simples e seguro** desenvolvido para demonstrar boas práticas de implementação de login, cadastro e controle de níveis de acesso em aplicações web.

**Objetivo principal**:  
Mostrar como criar um sistema de autenticação básico, mas seguro, utilizando PHP moderno, MySQL para persistência, TypeScript para o frontend e Vite como bundler. Inclui hashing de senhas, proteção contra SQL Injection (via PDO prepared statements), sessões seguras e níveis de acesso (ex: admin, usuário).

Repositório: https://github.com/letsmg/modulo-autenticacao-php-typescript-mysql

## Funcionalidades Concluídas

- Cadastro de usuários (com validação de email único e hashing de senha seguro)
- Login com autenticação de sessão
- Verificação de níveis de acesso (ex: redirecionamento baseado em role/nível)
- Estrutura de banco de dados básica (tabela `usuarios` com campos id, nome, email, senha_hash, nivel_acesso, etc.)
- Interface simples e responsiva (HTML + CSS + JavaScript/TypeScript)

## Funcionalidades Pendentes (a implementar)

- Edição de perfil/usuário (atualizar nome, email, senha, etc.)
- Exclusão de usuários (com confirmação e restrição por nível de acesso)
- Desativação/ativação de contas (campo `ativo` para bloquear acesso sem excluir)
- Recuperação de senha (reset via email/token)
- Proteções adicionais (CSRF tokens, rate limiting em login, 2FA opcional)

## Tecnologias Utilizadas

- **Backend**: PHP 8+
- **Banco de Dados**: MySQL (via PDO)
- **Frontend**: HTML5, CSS3, JavaScript + TypeScript
- **Build Tool**: Vite (para desenvolvimento rápido e bundling)
- **Gerenciamento de Pacotes**: npm
- **Controle de Versão**: Git
- **Outros**: Sessões PHP seguras, hashing com password_hash()

## Pré-requisitos

- PHP 8.1+ com extensões PDO e OpenSSL
- MySQL 5.7+ ou MariaDB
- Composer (opcional, se adicionar dependências)
- Node.js 18+ e npm (para Vite e TypeScript)
- Servidor web (Apache/Nginx ou PHP built-in server)

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/letsmg/modulo-autenticacao-php-typescript-mysql.git
   cd modulo-autenticacao-php-typescript-mysql
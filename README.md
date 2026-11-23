# Sistema Controle de OP

Sistema web desenvolvido em PHP (arquitetura MVC) e MySQL para rastreamento e controle de Ordens de Produção (OP) entre o almoxarifado e a produção, substituindo controles manuais por fluxo digital auditável.

## Demonstração Online
Acesse o ambiente de produção: https://controle-op.infinityfreeapp.com/controle-op/public/login.php

## Funcionalidades Principais
- **Gestão de Ordens de Produção:** Cadastro, edição, exclusão e filtros por número da OP, status e período.
- **Fluxo de Status Auditável:** Acompanhamento em tempo real (`Aguardando`, `Separada`, `Retirada`, `Interrompida`, `Produzida`) com registro de responsáveis e horários de cada etapa.
- **Rastreabilidade Física e Anexos:** Registro de localização (caixa/prateleira) e upload de comprovantes/laudos vinculados à OP.
- **Controle de Acesso por Perfil:** Autenticação segura com senhas criptografadas (`password_hash`) e níveis de permissão (`Admin`, `Almoxarife`, `Produção`).

## Tecnologias Utilizadas
- **Back-end:** PHP 8+ (PDO com Prepared Statements)
- **Banco de Dados:** MySQL
- **Front-end:** HTML5 semântico, CSS3 e Bootstrap 5
- **Arquitetura:** Padrão MVC (Models, Views e Controllers)

## Como Executar Localmente
1. Clone este repositório na pasta do seu servidor web (ex.: `/var/www/html/controle-op`).
2. Importe o script `banco/schema.sql` no seu MySQL.
3. Renomeie `config/config_exemplo.php` para `config/config.php` e preencha suas credenciais locais.
4. Acesse `http://localhost/controle-op/public/login.php`.

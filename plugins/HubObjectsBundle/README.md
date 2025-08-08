# Hub Objects Plugin for Mautic

**Autor:** Jules
**Versão:** 1.0.0

## Descrição

O plugin **Hub Objects** estende o Mautic com um sistema de objetos personalizados, inspirado na flexibilidade do HubSpot. Ele permite a criação e gerenciamento de novos tipos de dados e o estabelecimento de relacionamentos entre eles e os contatos nativos do Mautic.

Esta versão inicial implementa a fundação para três objetos principais:
- **Produtos**: Para gerenciar um catálogo de produtos ou serviços.
- **Contratos**: Para rastrear contratos associados a contatos, com produtos vinculados.
- **Oportunidades**: Para gerenciar o pipeline de vendas e oportunidades de negócio com contatos.

A arquitetura foi projetada para ser extensível, permitindo a adição de novos objetos e propriedades no futuro.

## Funcionalidades

- **Gerenciamento de Produtos**: Interface para criar, editar e apagar produtos.
- **Gerenciamento de Contratos**: Interface para criar, editar e apagar contratos, associando-os a contatos e produtos.
- **Gerenciamento de Oportunidades**: Interface para criar, editar e apagar oportunidades, associando-as a contatos.
- **API REST Completa**: Todos os objetos (Produtos, Contratos, Oportunidades) podem ser gerenciados via API, permitindo integrações e automações.

## Instalação

1.  Copie o diretório `HubObjectsBundle` para o diretório `plugins/` da sua instalação Mautic.
2.  Limpe o cache do Mautic executando o seguinte comando no terminal, a partir da raiz do seu projeto Mautic:
    ```bash
    php bin/console mautic:cache:clear
    ```
3.  Acesse sua instância do Mautic, clique no ícone de engrenagem (Configurações) no canto superior direito e vá para **Plugins**.
4.  Clique no botão **Instalar/Atualizar Plugins** no canto superior direito da página.
5.  O plugin "Hub Objects" deverá aparecer na lista.

## Uso

### Interface do Usuário (UI)

Após a instalação, um novo item de menu chamado **"Hub Objects"** aparecerá na barra de navegação principal à esquerda no Mautic. A partir dele, você pode acessar as seções para gerenciar **Produtos**, **Contratos** e **Oportunidades**.

### API

O plugin oferece uma API REST completa para todas as operações de CRUD. Para detalhes sobre os endpoints, parâmetros e exemplos de requisição, consulte o arquivo `API_DOCS.md` (a ser criado na próxima etapa).

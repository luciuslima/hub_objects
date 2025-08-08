# Tutorial Completo - Hub Objects Plugin

Este tutorial oferece um guia passo a passo para instalar, configurar e usar o plugin **Hub Objects** no Mautic.

## 1. Instalação

Siga estes passos para instalar o plugin na sua instância do Mautic:

1.  **Download do Plugin**: Primeiro, obtenha o diretório completo do plugin, chamado `HubObjectsBundle`.

2.  **Copiar para o Mautic**: Usando um cliente FTP/SFTP ou acesso ao terminal do seu servidor, copie o diretório `HubObjectsBundle` para a pasta `plugins/` da sua instalação Mautic.

3.  **Limpar o Cache**: É crucial limpar o cache do Mautic para que ele reconheça o novo plugin. Acesse o terminal na raiz do seu projeto Mautic e execute:
    ```bash
    php bin/console mautic:cache:clear
    ```

4.  **Instalar via Interface**:
    *   Faça login na sua conta Mautic como administrador.
    *   Clique no ícone de engrenagem (Configurações) no canto superior direito.
    *   No menu, selecione **Plugins**.
    *   Clique no botão **Instalar/Atualizar Plugins** no canto superior direito. O Mautic irá procurar por novos plugins.
    *   O plugin **"Hub Objects"** deve agora aparecer na lista de plugins.

5.  **Ativar o Plugin**: Embora a instalação o torne disponível, você pode precisar ativá-lo se ele não estiver ativado por padrão.

## 2. Uso via Interface do Mautic (UI)

Após a instalação, um novo item de menu, **Hub Objects**, estará visível na barra de navegação principal.

### Gerenciando Produtos

1.  **Acessar**: Navegue até **Hub Objects -> Produtos**.
2.  **Criar um Novo Produto**:
    *   Na página de listagem de produtos, clique no botão **"+ Novo"**.
    *   Preencha o formulário:
        *   **Nome**: O nome do seu produto ou serviço (ex: "Plano de Suporte Premium").
        *   **Descrição**: Detalhes sobre o produto.
        *   **Preço**: O valor do produto.
    *   Clique em **"Salvar e Fechar"** ou **"Aplicar"**.
3.  **Editar/Apagar**: Na lista de produtos, você encontrará ícones de ação para editar ou apagar cada item.

### Gerenciando Contratos

1.  **Acessar**: Navegue até **Hub Objects -> Contratos**.
2.  **Criar um Novo Contrato**:
    *   Clique no botão **"+ Novo"**.
    *   Preencha o formulário:
        *   **Nome**: Um nome descritivo para o contrato (ex: "Contrato Anual #123").
        *   **Valor**: O valor total do contrato.
        *   **Data de Início / Fim**: As datas de vigência do contrato.
        *   **Contato**: Comece a digitar o email de um contato existente no Mautic. Uma lista de sugestões aparecerá. Selecione o contato a ser associado.
        *   **Produtos**: Clique no campo e uma lista dos seus produtos cadastrados aparecerá. Selecione um ou mais produtos para vincular a este contrato.
    *   Salve o formulário.
3.  **Visualizar Detalhes**: Ao clicar em um contrato na lista, você verá todos os seus detalhes, incluindo o contato e os produtos associados, com links para suas respectivas páginas.

### Gerenciando Oportunidades

1.  **Acessar**: Navegue até **Hub Objects -> Oportunidades**.
2.  **Criar uma Nova Oportunidade**:
    *   Clique no botão **"+ Novo"**.
    *   Preencha o formulário:
        *   **Nome**: Nome da oportunidade (ex: "Venda de Consultoria - Empresa X").
        *   **Valor**: O valor estimado da oportunidade.
        *   **Estágio**: Selecione o estágio atual do funil de vendas (ex: Prospecção, Proposta, Negociação).
        *   **Data de Fechamento**: A data prevista para o fechamento do negócio.
        *   **Contato**: Associe um contato do Mautic, da mesma forma como nos contratos.
    *   Salve o formulário.

## 3. Uso via API

A API permite automatizar e integrar o gerenciamento dos objetos.

### Autenticação

A API utiliza a autenticação padrão do Mautic. Certifique-se de que a API está habilitada em **Configurações -> Configuração da API** e que você possui as credenciais (Client ID e Client Secret para OAuth2, ou um usuário/senha para Autenticação Básica).

Para mais detalhes, consulte o arquivo `API_DOCS.md`.

### Exemplos com `curl`

Substitua `https://seu-mautic.com` pela URL da sua instância e `SEU_TOKEN` pelo seu token de acesso (Bearer Token para OAuth2).

**Criar um Novo Produto**
```bash
curl -X POST https://seu-mautic.com/api/hubobjects/products/new \
-H "Authorization: Bearer SEU_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "name": "Produto via cURL",
    "description": "Este produto foi criado via API.",
    "price": 49.99
}'
```

**Criar um Novo Contrato (associado ao Contato ID 123)**
```bash
curl -X POST https://seu-mautic.com/api/hubobjects/contracts/new \
-H "Authorization: Bearer SEU_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "name": "Contrato de Suporte Anual",
    "value": 1200.00,
    "startDate": "2024-01-01 00:00:00",
    "contact": 123,
    "products": [1, 2]
}'
```

**Listar todos os Contratos**
```bash
curl -X GET https://seu-mautic.com/api/hubobjects/contracts \
-H "Authorization: Bearer SEU_TOKEN"
```

**Criar uma Nova Oportunidade (associada ao Contato ID 456)**
```bash
curl -X POST https://seu-mautic.com/api/hubobjects/opportunities/new \
-H "Authorization: Bearer SEU_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "name": "Oportunidade de Venda - Q4",
    "amount": 5000.00,
    "stage": "proposal",
    "contact": 456
}'
```

**Atualizar o Estágio de uma Oportunidade (ID 123)**
```bash
curl -X PATCH https://seu-mautic.com/api/hubobjects/opportunities/123/edit \
-H "Authorization: Bearer SEU_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "stage": "negotiation"
}'
```

**Apagar um Produto (ID 3)**
```bash
curl -X DELETE https://seu-mautic.com/api/hubobjects/products/3/delete \
-H "Authorization: Bearer SEU_TOKEN"
```

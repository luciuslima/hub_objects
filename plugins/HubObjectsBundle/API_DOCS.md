# Documentação da API - Hub Objects

## Introdução

Esta documentação detalha os endpoints da API para o plugin **Hub Objects**. A API permite o gerenciamento programático completo (CRUD - Criar, Ler, Atualizar, Apagar) dos objetos personalizados: **Produtos**, **Contratos** e **Oportunidades**.

A API segue os padrões REST e utiliza o formato JSON para todas as requisições e respostas.

## Autenticação

A API do Hub Objects utiliza o sistema de autenticação padrão do Mautic. Antes de fazer qualquer chamada, você precisa configurar e obter credenciais de API na sua instância do Mautic.

Consulte a [documentação oficial da API do Mautic](https://developer.mautic.org/#rest-api) para obter detalhes sobre como configurar a autenticação (OAuth 1a, OAuth 2 ou Autenticação Básica).

---

## Endpoints de Produtos

Base URL: `/api/hubobjects/products`

### Listar Produtos

Retorna uma lista paginada de produtos.

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/products`
- **Parâmetros de Query:**
  - `search` (string): Filtra os resultados por um termo de busca.
  - `limit` (int): Número de itens por página (padrão: 30).
  - `start` (int): Item inicial para a paginação (padrão: 0).
  - `orderBy` (string): Campo pelo qual ordenar.
  - `orderByDir` (string): Direção da ordenação (`asc` ou `desc`).

**Exemplo de Resposta:**
```json
{
    "products": [
        {
            "id": 1,
            "name": "Produto Exemplo A",
            "price": "99.90"
        },
        {
            "id": 2,
            "name": "Serviço de Consultoria",
            "price": "500.00"
        }
    ],
    "total": 2
}
```

### Obter um Produto

Retorna os detalhes de um produto específico.

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/products/{id}`

**Exemplo de Resposta:**
```json
{
    "product": {
        "id": 1,
        "name": "Produto Exemplo A",
        "description": "Uma descrição detalhada do produto.",
        "price": "99.90",
        "dateAdded": "2023-10-27T10:00:00+00:00",
        "createdBy": 1,
        "createdByUser": "Admin User"
    }
}
```

### Criar um Produto

Cria um novo produto.

- **Método:** `POST`
- **Endpoint:** `/api/hubobjects/products/new`
- **Corpo da Requisição (Payload):**
  - `name` (string, **obrigatório**): Nome do produto.
  - `description` (string): Descrição do produto.
  - `price` (float): Preço do produto.

**Exemplo de Payload:**
```json
{
    "name": "Novo Produto via API",
    "description": "Descrição do novo produto.",
    "price": 123.45
}
```

**Exemplo de Resposta (201 Created):**
```json
{
    "product": {
        "id": 3,
        "name": "Novo Produto via API",
        "description": "Descrição do novo produto.",
        "price": 123.45,
        ...
    }
}
```

### Editar um Produto

Atualiza um produto existente.

- **Método:** `PATCH` (para atualização parcial) ou `PUT` (para substituição completa)
- **Endpoint:** `/api/hubobjects/products/{id}/edit`

**Exemplo de Payload (PATCH):**
```json
{
    "price": 150.00
}
```

### Apagar um Produto

Remove um produto.

- **Método:** `DELETE`
- **Endpoint:** `/api/hubobjects/products/{id}/delete`

---

## Endpoints de Contratos

Base URL: `/api/hubobjects/contracts`

### Listar Contratos

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/contracts`

### Obter um Contrato

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/contracts/{id}`

### Criar um Contrato

- **Método:** `POST`
- **Endpoint:** `/api/hubobjects/contracts/new`
- **Corpo da Requisição (Payload):**
  - `name` (string, **obrigatório**): Nome do contrato.
  - `value` (float): Valor do contrato.
  - `startDate` (string): Data de início (`YYYY-MM-DD HH:MM:SS`).
  - `endDate` (string): Data de fim (`YYYY-MM-DD HH:MM:SS`).
  - `contact` (int): ID do contato do Mautic a ser associado.
  - `products` (array): Array de IDs de produtos a serem associados.

**Exemplo de Payload:**
```json
{
    "name": "Contrato de Suporte Anual",
    "value": 1200.00,
    "startDate": "2024-01-01 00:00:00",
    "contact": 123,
    "products": [1, 2]
}
```

### Editar um Contrato

- **Método:** `PATCH` ou `PUT`
- **Endpoint:** `/api/hubobjects/contracts/{id}/edit`

### Apagar um Contrato

- **Método:** `DELETE`
- **Endpoint:** `/api/hubobjects/contracts/{id}/delete`

---

## Endpoints de Oportunidades

Base URL: `/api/hubobjects/opportunities`

### Listar Oportunidades

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/opportunities`

### Obter uma Oportunidade

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/opportunities/{id}`

### Criar uma Oportunidade

- **Método:** `POST`
- **Endpoint:** `/api/hubobjects/opportunities/new`
- **Corpo da Requisição (Payload):**
  - `name` (string, **obrigatório**): Nome da oportunidade.
  - `amount` (float): Valor da oportunidade.
  - `stage` (string): Estágio da oportunidade (ex: `prospecting`, `closed_won`).
  - `closeDate` (string): Data de fechamento esperada (`YYYY-MM-DD HH:MM:SS`).
  - `contact` (int): ID do contato do Mautic a ser associado.

**Exemplo de Payload:**
```json
{
    "name": "Oportunidade de Venda - Q4",
    "amount": 5000.00,
    "stage": "proposal",
    "contact": 456
}
```

### Editar uma Oportunidade

- **Método:** `PATCH` ou `PUT`
- **Endpoint:** `/api/hubobjects/opportunities/{id}/edit`

### Apagar uma Oportunidade

- **Método:** `DELETE`
- **Endpoint:** `/api/hubobjects/opportunities/{id}/delete`

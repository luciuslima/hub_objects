# Documentação da API - Hub Objects

## Introdução

Esta documentação detalha os endpoints da API para o plugin **Hub Objects**. A API é **dinâmica**, o que significa que os endpoints se adaptam aos objetos personalizados que você cria.

A API permite o gerenciamento programático completo (CRUD) das **instâncias** dos seus objetos personalizados.

## Autenticação

A API utiliza o sistema de autenticação padrão do Mautic (OAuth ou Autenticação Básica). Consulte a [documentação oficial da API do Mautic](https://developer.mautic.org/#rest-api) para detalhes de configuração.


### Permissões da API

As ações da API são protegidas por permissões. Para que um usuário de API possa acessar os endpoints, seu Papel (Role) no Mautic precisa ter as seguintes permissões concedidas:

- **`hubobjects:instances:view`**: Para listar e ver detalhes das instâncias.
- **`hubobjects:instances:create`**: Para criar novas instâncias.
- **`hubobjects:instances:edit`**: Para editar instâncias existentes.
- **`hubobjects:instances:delete`**: Para apagar instâncias.

---

## Endpoints de Instâncias de Objeto

A API opera sobre as instâncias dos objetos que você define. As URLs são construídas usando o `slug` que você define para cada objeto no Construtor de Objetos.

**Base URL:** `/api/hubobjects/instances/{objectSlug}`

Substitua `{objectSlug}` pelo slug do seu objeto (ex: `produtos`, `faturas`, `imoveis`).

### Listar Instâncias

Retorna uma lista paginada de instâncias para um objeto específico.

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/instances/{objectSlug}`
- **Parâmetros de Query:**
  - `search` (string): Filtra os resultados. Veja a seção de busca por contato abaixo.
  - `limit` (int): Número de itens por página.
  - `start` (int): Item inicial para a paginação.
  - `orderBy` (string): Campo pelo qual ordenar.
  - `orderByDir` (string): `asc` ou `desc`.

### Obter uma Instância

Retorna os detalhes de uma instância específica.

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/instances/{objectSlug}/{id}`

### Criar uma Instância

Cria uma nova instância para um objeto.

- **Método:** `POST`
- **Endpoint:** `/api/hubobjects/instances/{objectSlug}/new`
- **Corpo da Requisição (Payload):**
  - O corpo do JSON deve conter os campos que você definiu para o objeto, além do ID do contato.
  - `contact` (int, **obrigatório**): ID do Contato do Mautic ao qual esta instância será associada.
  - `propriedade_1`: `valor_1`
  - `propriedade_2`: `valor_2`

**Exemplo (para um objeto 'faturas'):**
```json
{
    "numero_fatura": "FAT-2024-001",
    "valor": 250.50,
    "data_vencimento": "2024-12-31 00:00:00",
    "contact": 123
}
```

### Editar uma Instância

Atualiza uma instância existente.

- **Método:** `PATCH` ou `PUT`
- **Endpoint:** `/api/hubobjects/instances/{objectSlug}/{id}/edit`

### Apagar uma Instância

Remove uma instância.

- **Método:** `DELETE`
- **Endpoint:** `/api/hubobjects/instances/{objectSlug}/{id}/delete`

---

## Trabalhando com Objetos Relacionados a Contatos

Uma das principais funcionalidades da API é consultar e gerenciar os objetos que pertencem a um contato específico.

### Como encontrar os objetos de um contato?

Para encontrar todas as instâncias de um objeto associadas a um contato específico, use o endpoint de listagem com o parâmetro de busca `search`.

A busca deve ser no formato `contact:{id_do_contato}`.

**Exemplo: Encontrar todas as 'faturas' do Contato com ID 123**

- **Método:** `GET`
- **Endpoint:** `/api/hubobjects/instances/faturas?search=contact:123`

**Exemplo de Resposta:**
```json
{
    "instances": [
        {
            "id": 10,
            "properties": {
                "numero_fatura": "FAT-2024-001",
                "valor": 250.50,
                "data_vencimento": "..."
            },
            "contact": { "id": 123, ... }
        },
        {
            "id": 15,
            "properties": {
                "numero_fatura": "FAT-2024-002",
                "valor": 300.00,
                "data_vencimento": "..."
            },
            "contact": { "id": 123, ... }
        }
    ],
    "total": 2
}
```

### Como obter o ID de um objeto para atualizá-lo?

Conforme a resposta do exemplo acima, cada instância na lista retornada possui um `id` único. Para atualizar uma instância específica, basta usar este `id` no endpoint de edição.

**Exemplo: Atualizar a fatura com ID 15**

- **Método:** `PATCH`
- **Endpoint:** `/api/hubobjects/instances/faturas/15/edit`
- **Corpo da Requisição (Payload):**
  ```json
  {
      "valor": 320.00
  }
  ```

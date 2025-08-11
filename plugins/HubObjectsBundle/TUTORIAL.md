# Tutorial Completo - Hub Objects Plugin

Este tutorial oferece um guia passo a passo para instalar, configurar e usar o plugin **Hub Objects** no Mautic.

## 1. Instalação

(As instruções de instalação permanecem as mesmas do README.md)

1.  Copie o diretório `HubObjectsBundle` para a pasta `plugins/` da sua instalação Mautic.
2.  Limpe o cache do Mautic: `php bin/console mautic:cache:clear`
3.  Vá para **Configurações -> Plugins** e clique em **Instalar/Atualizar Plugins**.

## 2. Uso via Interface do Mautic (UI)

O fluxo de trabalho com o plugin agora é dividido em duas etapas principais:

**Etapa 1: Definir a Estrutura de um Objeto Personalizado**

Primeiro, você precisa definir que tipo de objeto deseja criar.

1.  **Acessar o Construtor**: No menu de Configurações (ícone de engrenagem), vá para **Plugins** e procure pelo item **Construtor de Objetos**.
2.  **Criar uma Nova Definição**:
    *   Na página de "Definições de Objeto", clique em **"+ Novo"**.
    *   Preencha o formulário de definição:
        *   **Nome do Objeto (Singular)**: Ex: "Fatura"
        *   **Nome do Objeto (Plural)**: Ex: "Faturas"
        *   **Slug**: Um identificador único, sem espaços (ex: `faturas`). **Este slug será usado na URL da API.**
    *   **Adicionar Propriedades (Campos)**:
        *   Clique no botão **"Adicionar Campo"**.
        *   **Nome do Campo**: O nome da propriedade (ex: "Número da Fatura").
        *   **Tipo do Campo**: Selecione o tipo de dado (Texto, Número, Data, etc.).
        *   Continue adicionando quantos campos forem necessários para o seu objeto.
    *   Clique em **"Salvar e Fechar"**.

**Etapa 2: Gerenciar os Registros (Instâncias) do seu Objeto**

Depois de definir um objeto (ex: "Faturas"), um novo item de menu aparecerá na navegação principal do Mautic para ele.

1.  **Acessar o Objeto**: Navegue até o menu **Objetos Personalizados -> Faturas** (ou o nome que você deu).
2.  **Criar um Novo Registro**:
    *   Na página de listagem, clique em **"+ Novo"**.
    *   O formulário que aparece será **dinâmico**, contendo exatamente os campos que você definiu na Etapa 1.
    *   Preencha os dados da fatura e salve.

### Gerenciando Permissões

Para que um usuário do Mautic possa ver e interagir com o Construtor de Objetos e com os dados dos objetos personalizados, um Administrador precisa conceder as permissões apropriadas ao seu Papel (Role).

As permissões são divididas em duas seções:
- **`Definições de Objetos Hub`**: Permite gerenciar o construtor (criar, editar, apagar os tipos de objeto).
- **`Instâncias de Objetos Hub`**: Permite gerenciar os dados dos objetos (criar, editar, apagar os registros).

### Usando Objetos em Campanhas

Você pode usar as propriedades dos seus objetos personalizados como condições em campanhas.

1.  No Construtor de Campanhas, adicione um novo item de **Condição**.
2.  Selecione a opção **"Verifica Propriedade de Objeto Hub"**.
3.  No formulário da condição:
    *   **Objeto**: Selecione o objeto personalizado que você quer verificar (ex: "Faturas").
    *   **Campo**: O dropdown de campos será atualizado com as propriedades do objeto selecionado. Escolha uma (ex: "Status").
    *   **Operador**: Escolha um operador de comparação (ex: "Igual a").
    *   **Valor**: Digite o valor a ser comparado (ex: "pago").
4.  Esta condição agora verificará se o contato possui uma fatura com o status "pago".


## 3. Uso via API

A API agora é dinâmica e usa o `slug` do objeto na URL.

### Autenticação
(Consulte `API_DOCS.md` para detalhes de autenticação).

### Exemplos com `curl`

Substitua `https://seu-mautic.com` pela URL da sua instância, `SEU_TOKEN` pelo seu token de acesso, e `{objectSlug}` pelo slug do seu objeto (ex: `faturas`).

**Criar uma Instância de 'Fatura' (associada ao Contato ID 123)**
```bash
curl -X POST https://seu-mautic.com/api/hubobjects/instances/faturas/new \
-H "Authorization: Bearer SEU_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "Numero da Fatura": "FAT-2024-001",
    "Valor": 250.50,
    "Data de Vencimento": "2024-12-31 00:00:00",
    "contact": 123
}'
```

**Listar todas as 'Faturas' do Contato com ID 123**
```bash
curl -X GET "https://seu-mautic.com/api/hubobjects/instances/faturas?search=contact:123" \
-H "Authorization: Bearer SEU_TOKEN"
```

**Atualizar uma 'Fatura' específica (ID 15)**
```bash
curl -X PATCH https://seu-mautic.com/api/hubobjects/instances/faturas/15/edit \
-H "Authorization: Bearer SEU_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "Valor": 275.00
}'
```

**Apagar uma 'Fatura' (ID 15)**
```bash
curl -X DELETE https://seu-mautic.com/api/hubobjects/instances/faturas/15/delete \
-H "Authorization: Bearer SEU_TOKEN"
```

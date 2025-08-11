# Hub Objects Plugin for Mautic

**Autor:** Lucius Lima
**Versão:** 1.1.0

## Descrição

O plugin **Hub Objects** transforma o Mautic em uma plataforma de dados mais flexível, introduzindo um **Construtor de Objetos Personalizados** inspirado na funcionalidade do HubSpot. Com este plugin, você pode criar, definir e gerenciar seus próprios objetos de negócio diretamente na interface do Mautic, estabelecendo relações entre eles e os contatos nativos.

Em vez de se limitar a objetos fixos, você pode agora modelar os dados que fazem sentido para o seu negócio, como "Assinaturas", "Imóveis", "Projetos" ou qualquer outra entidade.

## Funcionalidades Principais

- **Construtor Visual de Esquemas**: Crie novos objetos personalizados e defina suas propriedades (campos de texto, número, data, etc.) através de uma interface visual intuitiva.
- **Gerenciamento de Dados Dinâmico**: Para cada objeto que você cria, o plugin gera automaticamente as interfaces para listar, criar, editar e apagar os registros (instâncias) desse objeto.
- **API REST Dinâmica e Completa**: Todos os seus objetos personalizados e seus dados ficam instantaneamente disponíveis através de uma API REST genérica e poderosa, permitindo integrações e automações avançadas.
- **Integrações com Mautic (Fundação)**: A arquitetura suporta a integração das propriedades dos seus objetos com os recursos de marketing do Mautic, como filtros de Segmentos.

## Instalação

1.  Copie o diretório `HubObjectsBundle` para o diretório `plugins/` da sua instalação Mautic.
2.  Limpe o cache do Mautic executando o seguinte comando no terminal:
    ```bash
    php bin/console mautic:cache:clear
    ```
3.  Acesse sua instância do Mautic, vá para **Configurações -> Plugins** e clique em **Instalar/Atualizar Plugins**.
4.  O plugin "Hub Objects" deverá aparecer na lista.

## Uso

Para um guia detalhado com exemplos passo a passo, consulte o arquivo `TUTORIAL.md`.

### API

Para detalhes sobre a API dinâmica, incluindo como encontrar objetos relacionados a um contato, consulte o arquivo `API_DOCS.md`.

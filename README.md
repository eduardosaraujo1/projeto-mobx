# MVP MOBX

## Resumo

O MOBX é um sistema destinado à organização interna de imobiliárias que o utilizarem. Seu objetivo principal é possibilitar a busca e gerenciamento de imóveis importados de uma planilha Excel. Essa planilha contém informações como número do imóvel, endereço, localização (lado praia ou morro), IPTU, aluguel, e bairro. O sistema não possui funcionalidade externa e é restrito ao uso interno.

## Requisitos Funcionais

### 1. Cadastro e Login

Usuários autenticados podem acessar o sistema com um login seguro. O sistema permite o cadastro de novos usuários, garantindo acesso controlado.

### 2. Cadastro, Remoção e Edição de Imóveis (CRUD)

O sistema permite o cadastro de imóveis com base nos dados importados de uma planilha Excel. Além disso, os usuários podem editar informações existentes ou remover imóveis que não estão mais em uso.

### 3. Alteração de Status dos Imóveis

Imóveis podem ter seus status alterados entre opções como: 'vendido', 'alugado', 'livre', ou outros status definidos pela imobiliária, garantindo controle atualizado sobre a situação de cada propriedade.

### 4. Upload de Documentos Relacionados aos Imóveis

Os usuários podem anexar documentos relevantes a cada imóvel, como contratos, termos e outros arquivos necessários para a operação da imobiliária.

### 5. Cadastro de Locadores e Vendedores (Clientes)

O sistema permite o registro de locadores e vendedores, incluindo informações de contato e nome, para manter um controle organizado sobre as partes interessadas nos imóveis.

### 6. Tela de Perfil da Imobiliária

Cada imobiliária terá acesso a uma tela de perfil personalizada. Essa tela conterá informações como nome da imobiliária, endereço, contatos principais, e uma breve descrição. Os administradores poderão editar essas informações para mantê-las sempre atualizadas.

### 7. Dashboard de Controle

Um painel principal que fornece uma visão geral dos imóveis cadastrados, incluindo status atual (livre, vendido, alugado), quantidade de imóveis disponíveis, e relatórios sobre desempenho (como tempo médio de aluguel ou venda).

### 8. Relatórios e Exportação de Dados

O sistema permitirá a geração de relatórios detalhados sobre os imóveis, locadores, vendedores e contratos. Esses relatórios poderão ser exportados para Excel ou PDF, facilitando a análise de dados e a apresentação de informações em reuniões ou auditorias.

### 9. Sistema de Permissões de Usuário

O MOBX contará com diferentes níveis de acesso, como administradores, gerentes e colaboradores. Cada nível terá permissões específicas, garantindo segurança e controle sobre as funcionalidades do sistema.

### 10. Notificações e Alertas

O sistema enviará notificações internas para alertar sobre contratos vencendo, status de imóveis alterados, ou pendências importantes, garantindo que a equipe esteja sempre informada sobre as atualizações relevantes.

### 11. Integração com Agenda

Uma funcionalidade que possibilita agendar visitas aos imóveis diretamente pelo sistema. Esses agendamentos podem ser vinculados a um calendário compartilhado, facilitando o controle das visitas.

### 12. Busca Avançada de Imóveis

Um sistema de busca detalhado, com filtros como localização, status, valor de aluguel ou venda, e características específicas dos imóveis (número de quartos, vagas de garagem, etc.). Isso agiliza a localização de imóveis dentro do sistema.

### 13. Histórico de Alterações

Cada imóvel terá um histórico detalhado das alterações realizadas, como mudanças de status, upload de documentos, ou edições de dados. Esse histórico garante transparência e rastreabilidade das ações no sistema.

## Tabelas banco de dados

<details>
<summary>Expandir</summary>

### users

-   id (PK)
-   nome
-   email (único)
-   senha (hashed)
-   nivel_acesso (admin, gerente, colaborador)
-   data_criacao
-   data_atualizacao

### imobiliarias

-   id (PK)
-   fk_id_dono (FK user)
-   nome
-   endereco
-   caminho_foto
-   contato
-   data_criacao
-   data_atualizacao

### clientes

-   id (PK)
-   cpf
-   nome
-   email
-   telefone
-   endereco
-   tipo (vendedor ou locador)
-   data_criacao
-   data_atualizacao

### imoveis

-   id (PK)
-   fk_id_cliente (FK)
-   caminho_foto
-   endereco
-   descricao
-   status (livre, vendido, alugado, etc.)
-   valor (decimal(15, 2))
-   data_criacao
-   data_atualizacao

### documentos_imovel

-   id (PK)
-   fk_id_imovel (FK para imoveis)
-   nome_arquivo
-   caminho_arquivo
-   data_upload
-   data_atualizacao

### logs_imovel

-   id (PK)
-   fk_id_imovel (FK para imoveis)
-   fk_id_usuario (FK para usuarios)
-   tipo_alteracao (status, descricao, etc.)
-   descricao_alteracao
-   data_alteracao

</details>

## Relações banco de dados

<details>
<summary>Expandir</summary>

1. Um usuário pode ter **varias** imobiliarias (um pra muitos)
2. **Vários** usuários colaboradores podem ter acesso a **várias** imobiliárias (muitos para muitos)
3. Um imobiliária pode ter **vários** imóveis (um pra muitos)
4. Um cliente pode ter **vários** imóveis (um pra muitos)
5. Um imóvel pode ter **vários** documentos (um pra muitos)
6. Um imóvel pode ter **várias** alterações (um pra muitos)

</details>

## Roles de usuário ([implementação](https://spatie.be/docs/laravel-permission/v6/introduction))

<details>
<summary>Expandir</summary>

LCRUD (List, Create, Read, Update, Delete)

### Administrador (painel admin)

-   LCRUD Usuários
-   LCRUD Imobiliárias
-   LCRUD Imóveis
-   LCRUD Clientes
-   \_CRUD Documentos
    -   _listagem não é necessária, está atrelado à tela do imóvel_
-   L_R\_\_ Logs

### Gerente da imobiliaria (dashboard)

-   L\_\R\_\_ Imobiliárias (apenas suas próprias)
-   LCRUD Imóveis (de sua imobiliária)
-   LCRUD Clientes (de imóveis da sua imobiliária)
-   \_CRUD Documentos (de imóveis da sua imobiliária)
-   L_R\_\_ Logs (de imóveis de sua imobiliária)

### Colaborador do gerente (dashboard)

-   L\_\R\_\_ Imobiliárias
-   L_R\_\_ Imóveis
-   L_R\_\_ Clientes
-   \_\_R\_\_ Documentos
</details>

## Telas

### Painel Admin

<details>
<summary>Expandir</summary>

-   Tela home do usuário "Administrador"
-   Possui navegação para:

    -   Listagem Usuários
    -   Listagem Imobiliárias
    -   Listagem Imóveis
    -   Listagem Clientes

    </details>

### Dashboard

<details>
<summary>Expandir</summary>

-   Tela home do usuário "Gerente" e "Colaborador", com conteúdo a depender de seu nível de acesso
-   Possui navegação para:

    -   Listagem Imobiliárias (botão dropdown escolher na topbar)
    -   Listagem Imóveis (sidebar)
    -   Listagem Clientes (sidebar)

</details>

### Usuários

<details>
<summary>Expandir</summary>

#### Listagem Usuários

-   Lista de usuários no sistema, incluindo o atual
-   Pesquisa por nome
-   Navegação para cadastro, edição e visualização

#### Cadastro de Usuário

-   Cadastro para novo usuário, aplicando validações necessárias

#### Edição de Usuário

-   Alteração de usuário existente, aplicando validações necessárias

#### Visualização de Usuário

-   Visualização de dados mais detalhados do usuário
-   Opção de exclusão de usuário com confirmação
-   Acessível pelo colaborador (tela de perfil) e administrador (funções destrutivas)
</details>

### Imobiliária

<details>
<summary>Expandir</summary>

#### Seleção de imobiliária

-   Lista de imobiliárias do gerente logado atualmente.
-   Seleção necessária antes de navegar para telas de imóveis e clientes
-   Acessível pelo colaborador

#### Listagem imobiliárias

-   Lista de imobiliárias no sistema
-   Pesquisa por nome
-   Navegação para cadastro, edição e visualização
-   Acessível pelo administrador

#### Cadastro de imobiliária

-   Cadastro para novo imobiliária, aplicando validações necessárias
-   Acessível pelo administrador

#### Edição de imobiliária

-   Alteração de imobiliária existente, aplicando validações necessárias
-   Acessível pelo administrador e gerente com limitações

#### Visualização de imobiliária

-   Visualização de dados mais detalhados da imobiliária
-   Opção de inativação de imobiliária pelo administrador
-   Acessível pelo gerente (requisito #6) com exceção da função de inativação
</details>

### Imóveis

<details>
<summary>Expandir</summary>

#### Listagem imóveis

-   Lista de imóveis da imobiliária selecionada
-   Para navegar aqui, a imobiliária deve ser sido selecionada previamente na Seleção de Imobiliária
-   Pesquisa por endereço do imóvel ou nome do cliente
-   Navegação para cadastro, edição e visualização de imóveis
-   Acessível pelo administrador e gerente com limitações (apenas próprias imobiliarias)

#### Cadastro de imóvel

-   Cadastro para novo imóvel, aplicando validações necessárias
-   Acessível pelo gerente

#### Edição de imóvel

-   Alteração de imóvel existente, aplicando validações necessárias
-   Acessível pelo gerente

#### Visualização de imóvel

-   Visualização de dados mais detalhados do imóvel (se necessário)
-   Opção de remoção de imóvel (para o gerente)
-   Opção de remoção de cliente (para o gerente)
-   Visualização de cliente atual
-   Navegação para alterar cliente do imóvel
-   Acessível pelo colaborador, exceto remoção de imóvel e cliente

#### Alterar cliente de imóvel

-   Exibe campo para digitar o CPF do cliente que deve ser cadastrado
-   Caso cliente seja encontrado, perguntar se os dados estão corretos antes de cadastrar, se não estiverem corretos enviar para edição
-   Caso cliente não seja encontrado, exibir formulario de cadastro de novo cliente que o cadastra e repassa pela tela de perguntar se os dados estão corretos
-   Sempre opções de Cancelar e voltar para tela de visualização de imóvel
-   Acessível pelo gerente

#### Documentos do imóvel

-   Tela para upload, download e remoção de documentos do imóvel
-   Acessível pelo colaborador para download; upload e remoção pelo gerente

#### Logs do imóvel

-   Tela para visualizar as alterações efetuadas no imóvel
-   Filtravel por período
-   Acessível pelo gerente
</details>

### Clientes

<details>
<summary>Expandir</summary>

#### Listagem de Cliente

-   Lista de clientes da imobiliária selecionada
-   Pesquisa por cpf e/ou nome
-   Navegação para cadastro, edição e visualização
-   Acessível pelo administrador e gerente com limitações (apenas clientes de sua imobiliária)

#### Cadastro de cliente

-   Cadastro para novo cliente, aplicando validações necessárias
-   Acessível pelo gerente

#### Edição de cliente

-   Alteração de cliente existente, aplicando validações necessárias
-   Acessível pelo gerente

#### Visualização de cliente

-   Visualização de dados mais detalhados do cliente (se necessário)
-   Acessível pelo colaborador
</details>

# Roadmap

-   [ ] PROTOTYPE - Planejar estrutura do banco de dados, telas existentes, roles de usuário
-   [ ] ADMIN - Criar painel administrativo e LCRUDs dos modelos eloquentes
-   [ ] DASHBOARD - Criar dashboard do gerente e controlar seu acesso para os LCRUDs dos modelos eloquentes
-   [ ] PERMISSIONS - Criar tela para um gerente controlar o acesso de seus colaboradores

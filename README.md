# MVP MOBX

## Instalação para Ambiente de Desenvolvimento

1. Tenha [docker](https://www.docker.com/) instalado e funcionando
2. Clone o repositório utilizando `git clone https://github.com/eduardosaraujo1/projeto-mobx`
3. Entre na raiz do repositório com `cd projeto-mobx`
4. Crie o arquivo `.env` copiando do arquivo `.env.example`

- Linux/MacOS: `cp .env.example .env`
    - Windows: `copy .env.example .env`

5. Abra o arquivo `.env` com seu editor de código e altere os dados do banco de dados para o que desejar
    - Defina uma senha segura caso queira utilizar outra ferramenta para colocar em produção
6. Caso esteja em Linux, execute o comando `bash ./saildeploy.sh` para inicializar o projeto

    - Em Windows, utilize os comandos abaixo

    ```sh
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php84-composer:latest \
        composer install --ignore-platform-reqs

    ./vendor/bin/sail up -d
    ./vendor/bin/sail npm install
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan config:cache
    ./vendor/bin/sail artisan migrate --seed
    ```

    - Por fim, utilize `./vendor/bin/sail npm run dev` para iniciar o [Vite](https://vite.dev/)

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

### Modelagem

<details>
<summary>Expandir</summary>

![MODELAGEM](/docs/database/model.png)

</details>

## Permissões

<details>
<summary>Expandir</summary>

### Administrador

#### Usuários

- Listar
- Ler
- Criar
- Editar
- Apagar

#### Imobiliárias

- Listar
- Ler
- Criar
- Editar
- Apagar

#### Cliente

- Listar
- Ler
- Criar
- Editar

#### Imoveis

- Listar
- Ler
- Criar
- Editar
- Apagar

#### Documentos imoveis

- Listar
- Ler (download)
- Criar (upload)
- Apagar

#### Logs imoveis

- Listar
- Ler
- Criar (não diretamente)

### Gerente

#### Imobiliárias

- Listar (apenas suas próprias)
- Ler (apenas suas próprias)

#### Imóveis

- Listar (de sua imobiliária)
- Ler (de sua imobiliária)
- Criar (de sua imobiliária)
- Editar (de sua imobiliária)
- Apagar (de sua imobiliária)

#### Clientes

- Listar (de imóveis da sua imobiliária)
- Ler (de imóveis da sua imobiliária)
- Criar (de imóveis da sua imobiliária)
- Editar (de imóveis da sua imobiliária)
- Apagar (de imóveis da sua imobiliária)

#### Documentos de imóveis

- Ler (download - de imóveis da sua imobiliária)
- Criar (upload - de imóveis da sua imobiliária)
- Apagar (de imóveis da sua imobiliária)

#### Logs de imóveis

- Listar (de imóveis da sua imobiliária)
- Ler (de imóveis da sua imobiliária)

### Colaborador

#### Imobiliárias

- Listar
- Ler

#### Imóveis

- Listar
- Ler

#### Clientes

- Listar
- Ler

#### Documentos de imóveis

- Ler (download)

</details>

## Hierarquia

1. Administrador
2. Gerente
3. Colaborador
4. SelectedImobiliária
5. Authed
    - Self (current_user === resource_user)
6. All

## Telas

### Design

Esboços das telas estão localizadas no [figma](https://www.figma.com/design/3C5ob6CECygrrGYAjsHRY9/Mobx)

### Login | All

- **Descrição:** campos 'email' e 'senha' e botão de login.
- **Navegação:**

    - Selecionar Imobiliária

- **Use Cases:**

    - login | All

### Gerenciar Usuários | Admin

- **Descrição:** pesquisa e visualização de usuários.
- **Navegação:**

    - Cadastrar Usuário
    - Visualizar Usuário
    - Navbar

- **Use Cases:**

    - pesquisar_usuario | Admin

### Cadastrar Usuário | Admin

- **Descrição:** cadastro de usuário novo, com nome, email, senha e status administrativo.
- **Navegação:**

    - Navbar

- **Use Cases:**

    - cadastrar_usuario

### Visualizar Usuário | Self or Admin

- **Descrição:** visualização dos dados do usuário selecionado, com edição para administradores.
- **Navegação:**

    - Navbar

- **Use Cases:**

    - editar_usuario
    - remover_usuario

### Alterar Senha | Self

- **Descrição:** campos para alterar senha do usuário atual e confirmar.
- **Navegação:**

    - Navbar
    - Voltar

- **Use Cases:**

    - change_password

### Selecionar Imobiliária | Authed

- **Descrição:** seleção de imobiliária para gerenciar; com barra de pesquisa e botão para cadastro; navbar para gerenciar usuários.
- **Navegação:**

    - Navbar
    - Cadastrar Imobiliária
    - Perfil Imobiliária

- **Use Cases:**

    - select_imobiliaria | Colaborador
    - search_imobiliaria | Colaborador
    - create_imobiliaria | Admin

### Cadastrar Imobiliária | Admin

- **Descrição:** criar nova imobiliária.
- **Navegação:**

    - Navbar
    - Voltar

- **Use Cases:**

    - cadastrar_imobiliaria
    - upload_logo_imobiliaria

### Perfil Imobiliária | Colaborador.SelectedImobiliaria

- **Descrição:** ver dados da imobiliária, com edição e exclusão (para administrador).
- **Navegação:**

    - Navbar
    - Sidebar
    - Gerenciar Membros Imobiliaria

- **Use Cases:**

    - editar_imobiliaria | Admin
    - remover_imobiliaria | Admin

### Gerenciar Membros Imobiliária | Gerente.SelectedImobiliaria

- **Descrição:** adicionar ou remover pessoas com acesso à imobiliária e seus cargos.
- **Navegação:**

    - Navbar
    - Sidebar

- **Use Cases:**

    - pesquisar_usuario | Gerente
    - alterar_permissao_usuario | Gerente

### Dashboard Imobiliária | Colaborador.SelectedImobiliaria

- **Descrição:** ver gráficos e relatórios relacionados aos imóveis.
- **Navegação:**

    - Navbar
    - Sidebar

- **Use Cases:**

    - exportar_relatorio | Colaborador.SelectedImobiliaria

### Gerenciar Clientes | Colaborador.SelectedImobiliaria

- **Descrição:** ver clientes atrelados à imobiliária.
- **Navegação:**

    - Navbar
    - Sidebar
    - Cadastrar Cliente
    - Visualizar Cliente

- **Use Cases:**

    - pesquisar_cliente | Colaborador.SelectedImobiliaria

### Cadastrar Cliente | Gerente.SelectedImobiliaria

- **Descrição:** **modal** para adicionar cliente à imobiliária.
- **Navegação:**

    - N/A (é um modal)

- **Use Cases:**

    - cadastrar_cliente | Gerente.SelectedImobiliaria

### Visualizar Cliente | Colaborador.SelectedImobiliaria

- **Descrição:** ver dados cadastrados de um cliente, com edição para gerentes.
- **Navegação:**

    - Navbar
    - Sidebar

- **Use Cases:**

    - editar_cliente | Gerente.SelectedImobiliaria

### Gerenciar Imóveis | Colaborador.SelectedImobiliaria

- **Descrição:** ver imóveis relacionados à imobiliária.
- **Navegação:**

    - Navbar
    - Sidebar
    - Cadastrar Imóvel

- **Use Cases:**

    - pesquisar_imovel | Colaborador.SelectedImobiliaria

### Cadastrar Imóvel | Gerente.SelectedImobiliaria

- **Descrição:** cadastrar novo imóvel à imobiliária através do upload de um excel.
- **Navegação:**

    - Navbar
    - Sidebar
    - Voltar

- **Use Cases:**

    - download_template | Gerente.SelectedImobiliaria
    - upload_data | Gerente.SelectedImobiliaria
    - verify_template | Gerente.SelectedImobiliaria
    - cadastrar_imovel | Gerente.SelectedImobiliaria

### Cadastrar Imóvel Manualmente | Gerente.SelectedImobiliaria

- **Descrição:** cadastrar novo imóvel à imobiliária através de um formulário.
- **Navegação:**

    - Navbar
    - Sidebar
    - Voltar

- **Use Cases:**

    - cadastrar_imovel | Gerente.SelectedImobiliaria

### Visualizar Imóvel | Colaborador.SelectedImobiliaria

- **Descrição:** ver dados cadastrados de um imóvel, com edição para gerentes.
- **Navegação:**

    - Navbar
    - Sidebar
    - Gerenciar Documentos Imóvel
    - Histórico de Imóvel

- **Use Cases:**

    - delete_imovel | Gerente.SelectedImobiliaria
    - edit_imovel | Gerente.SelectedImobiliaria

### Gerenciar Documentos Imóvel | Colaborador.SelectedImobiliaria

- **Descrição:** ver e modificar documentos atrelados ao imóvel.
- **Navegação:**

    - Navbar
    - Sidebar

- **Use Cases:**

    - upload_document | Gerente.SelectedImobiliaria
    - remove_document | Gerente.SelectedImobiliaria
    - download_document | Colaborador.SelectedImobiliaria

### Histórico de Alterações Imóvel | Colaborador.SelectedImobiliaria

- **Descrição:** ver o histórico de alterações de imóvel.
- **Navegação:**

    - Navbar
    - Sidebar

- **Use Cases:**

    - N/A (readonly page)

## Grande mudança: registro de aluguel

Para obter o "tempo médio de aluguel", "alertas sobre contratos vencendo" e "alertas de status de imóveis alterados" é necessário acompanhar quais alugueis foram feitos.

Para isso, ao em vez de definir cliente, status e valor manualmente, faremos registro em uma tabela "aluguel" com os valores:

- Valor
- Data inicio
- Data final (nullable)
- Cliente
- mark_finalizado (bool)

Tudo isso é editavel, mas não é possível remover um aluguel, apenas "marcar como finalizado". uma venda é definida separadamente e é uma flag (checkbox) do imóvel (vendido?). Se essa for marcada o valor da venda deve ser dado em outro campo.
Deve ser possível também "renovar" um aluguel, que o extende por mais um mês
Por fim, remover 'status' e 'cliente' do imovel dado que o status de "livre", "alugado" ou "vendido" é definido se o mais recente aluguel foi finalizado, está ativo, ou

# Roadmap

- [x] DOCS - Separar o único fluxo de telas em três: um para cada nível, como foi feito no
- [x] fix/permissions - Verificar se nenhuma permissão indevida está dada (e.g um usuário alterar o valor de um cliente artificialmente pra um cliente que não pertence a imobiliaria)
- [x] feat/RF13 - Adicionar upload de documentos aos logs
- [x] feat/deletion - Adicionar funcionalidade de deleção de usuários, imóveis, clientes e imobiliarias
- [ ] feat/RF12-advanced-search - Mecanismo de busca de imoveis avançada (localização, status, valor de aluguel ou venda)
- [ ] feat/dashboard - Gráfico de divisão para situação de imóveis
- [ ] feat/relatorios - Gerar aluguel
- [ ] feat/track-aluguel - Criar mecanismos de log para saber qual é o tempo médio de um aluguel e mostrar no dashboard
- [ ] feat/notifications - Sistema de notificar os usuários de acontecimentos relevantes
    - Requirements: table notifications, user_notification; job for triggering notifications
- [ ] feat/visit-schedule - Agendar lembretes para a visita de um imóvel
    - Requirements: table visit

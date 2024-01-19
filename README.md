# :computer: API Jogo dos Cinco

Bem-vindo à API do "Jogo dos Cinco"! Esta API foi desenvolvida para se integrar perfeitamente ao bot do Discord
disponível em [bot-jogo-dos-cinco](https://github.com/LuigiTaka/bot-jogo-dos-cinco). Com a API, você pode criar
perguntas e suas respectivas respostas, adicionando uma camada extra de interatividade ao jogo.

## Sobre o Projeto

Este projeto nasceu à beira da praia, criado como uma maneira de passar o tempo e trocar uma idéia.

## Variáveis de ambiente

- ``MYSQL_ROOT_PASSWORD``: Senha do usuário root do MySQL. Essa senha é necessária para acessar e administrar o banco de
  dados MySQL.

- ``MYSQL_DATABASE``: Nome do banco de dados MySQL que será utilizado pelo projeto. Neste caso, o banco de dados é
  chamado "jogo_dos_cinco".

- ``MYSQL_USER``: Nome do usuário do MySQL que será utilizado pelo projeto.

- ``MYSQL_HOST``: Nome do host do MySQL. Indica onde o servidor do banco de dados está localizado.

- ``MYSQL_PASSWORD``: Senha associada ao usuário do MySQL. É a senha utilizada para autenticar e acessar o banco de
  dados.

- ``TOKEN_API``: Token de autenticação utilizado para métodos POST e PUT da API. Essa medida de segurança ajuda a
  garantir que apenas usuários autorizados possam realizar alterações no sistema.

- ``API_PORT``: Porta em que a API estará disponível. No exemplo, a API estará acessível na porta 81.

## Como Rodar o Projeto

Use o seguinte comando para construir e iniciar o projeto:

```shell
docker compose build && docker compose up -d
```

Verifique se o servidor está rodando acessando [localhost/api/pergunta](http://localhost/api/pergunta).
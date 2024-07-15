<p align="center">
  <img src="https://framerusercontent.com/images/f0btmN2GtVDhwuoOUM5xAjorM.png" width="400" alt="Kanastra Logo" style="background-color:white; padding:10px; border-radius:10px;">
</p>

# Kanastra Hiring Challenge

## Índice

- [Sobre a Aplicação](#sobre-a-aplicação)
- [Inicialização do Projeto](#inicialização-do-projeto)
- [Aplicações Auxiliares](#aplicações-auxiliares)
- [Documentação da API](#documentação-da-api)
- [Testes](#testes)
- [Links Úteis](#links-úteis)

## Sobre a Aplicação

Esta aplicação foi desenvolvida utilizando Laravel 10 com PHP 8.2. O principal objetivo foi criar uma aplicação altamente escalável e de fácil manutenção. Para isso, foram utilizados jobs para processamento de tarefas em segundo plano, garantindo que a aplicação não fique travada enquanto processa tarefas mais pesadas. Jobs mais pesados são gerenciados através de batches, garantindo melhor gestão de recursos.

### Tecnologias Utilizadas

- **Laravel 10**
- **PHP 8.2**
- **Laravel Sail**
- **Laravel Telescope**
- **Laravel Horizon**
- **Laravel Pulse**

## Inicialização do Projeto

1. Clone o repositório:

```bash
git clone https://github.com/IsraelPinheiro/kanastra-hiring-challenge.git
```

2. Acesse a pasta do projeto:

```bash
cd kanastra-hiring-challenge
```

3. Copie `.env.example` para `.env` manualmente ou com:

```bash
cp .env.example .env
```

4. Execute o build e rode os containers da aplicação através do Sail:

```bash
./vendor/bin/sail up
```

Ou, para o modo detached:

```bash
./vendor/bin/sail up -d
```

Este mesmo comando pode ser utilizado isoladamente apenas para subir os containers da aplicação.
Caso necessário, você pode refazer o build dos containers com o comando:

```bash
./vendor/bin/sail build --no-cache
```

5. Gere a chave da aplicação com o comando:

```bash
./vendor/bin/sail artisan key:generate
```

6. Rode as migrations do banco de dados com o comando:

```bash
./vendor/bin/sail artisan migrate
```

7. A aplicação estará acessível em `http://kanastra-hiring-challenge.test`.

## Aplicações Auxiliares

O sistema conta com algumas aplicações auxiliares que podem ser acessadas através dos seguintes links:

- **Laravel Telescope**: Ferramenta de depuração e monitoramento.
  - [http://kanastra-hiring-challenge.test/telescope](http://kanastra-hiring-challenge.test/telescope)
  
- **Laravel Horizon**: Gerenciador de filas robusto.
  - [http://kanastra-hiring-challenge.test/horizon](http://kanastra-hiring-challenge.test/horizon)
  
- **Pulse**: Painel de monitoramento do status da aplicação com capacidade de gerenciamento em cluster.
  - [http://kanastra-hiring-challenge.test/pulse](http://kanastra-hiring-challenge.test/pulse)

## Documentação da API

A documentação da API está disponível no Postman Online [aqui](https://documenter.getpostman.com/view/3768689/2sA3kPq4gx#959285fe-a181-4320-8482-0f2b5258224d).

## Testes

Os testes podem ser executados através do comando

```bash
./vendor/bin/sail artisan test
```

### Links Úteis

- [Documentação da API](https://documenter.getpostman.com/view/3768689/2sA3kPq4gx#959285fe-a181-4320-8482-0f2b5258224d)
- [Documentação de Requisitos](https://kanastra.notion.site/Hiring-Challenge-Soft-Engineers-Backend-Take-Home-65cd4195a1ff42f68ff446f8859d2d7f#28f7f34ac79145e383e01f9c510fd133)
- [Repositório no GitHub](https://github.com/IsraelPinheiro/kanastra-hiring-challenge)
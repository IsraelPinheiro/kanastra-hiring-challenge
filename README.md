<p align="center">
  <img src="https://framerusercontent.com/images/f0btmN2GtVDhwuoOUM5xAjorM.png" width="400" alt="Kanastra Logo"></a>
</p>

# Kanastra Hiring Challenge

## Sobre a Aplicação

Foi desenvolvida utilizando Laravel 10 com PHP 8.2, o principal objetivo foi ter uma aplicação altamente escalável e de fácil manutenção, para isso, foram utilizados jobs para processamento de tarefas em segundo plano, garantindo que a aplicação não fique travada enquanto processa tarefas mais pesadas, além disso, jobs mais besados são gerenciados através de batches, garantindo melhor gestão de recursos.

## Inicialização do Projeto

1. Clone o repositório;
2. Acesse a pasta do projeto;
3. Copie .env.example para .env manualmente ou com `cp .env.example .env`;
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

O Sistema conta com algumas aplicações auxiliares que podem ser acessadas através dos seguintes links:

### Laravel Telescope

O Laravel Telescope é uma ferramenta de depuração e monitoramento elegante para o framework Laravel. Ele pode ser acessado em [http://kanastra-hiring-challenge.test/telescope](http://kanastra-hiring-challenge.test/telescope).

### Laravel Horizon

O Laravel Horizon é um gerenciador de filas robusto que fornece maior poder de fogo para o Laravel. Ele conta com uma interface web que pode ser acessada em [http://kanastra-hiring-challenge.test/horizon](http://kanastra-hiring-challenge.test/horizon).

### Pulse

O Laravel Pulse é um painel de monitoramento que fornece informações sobre o status da aplicação. Ele pode ser acessado em [http://kanastra-hiring-challenge.test/pulse](http://kanastra-hiring-challenge.test/pulse).


## Documentação da API

A documentação da API está disponível no Postman Online [aqui](https://documenter.getpostman.com/view/3768689/2sA3kPq4gx#959285fe-a181-4320-8482-0f2b5258224d).

## Testes

Os testes podem ser executados através do comando

```bash
./vendor/bin/sail artisan test
```

### Links Úteis

* [Documentação da API](https://documenter.getpostman.com/view/3768689/2sA3kPq4gx#959285fe-a181-4320-8482-0f2b5258224d)
* [Documentação de Requisitos](https://kanastra.notion.site/Hiring-Challenge-Soft-Engineers-Backend-Take-Home-65cd4195a1ff42f68ff446f8859d2d7f#28f7f34ac79145e383e01f9c510fd133)
* [Repositório no GitHub](https://github.com/IsraelPinheiro/kanastra-hiring-challenge)
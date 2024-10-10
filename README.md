# Imob API

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)

Este projeto é uma API RESTful contruída com **PHP** e **Laravel** para imobiliárias.

## Como usar o projeto

Instale o Docker caso não possuir em sua maquina

### Clone o Repositório
```sh
git clone https://github.com/Hiago-Laureano/imob-api.git
```

### Crie o arquivo .env
```sh
cp .env.example .env
```

### Atualize o arquivo .env com os dados abaixo, o restante altere com os dados que desejar
```dosini
DB_CONNECTION=mysql
DB_HOST=db
```

### Suba os containers do projeto
```sh
docker-compose up -d
```

### Acesse o container da API para poder usar os comandos do Laravel
```sh
docker-compose exec api bash
```

### Instale as dependências do projeto
```sh
composer install
```

### Gere a key do projeto Laravel
```sh
php artisan key:generate
```

### Acessar o projeto

[http://localhost:8000](http://localhost:8000)

### Upload de imagens e Factory para imagens

Para permitir acesso público às imagens postadas execute o seguinte comando:
```sh
php artisan storage:link
```

Caso for executar as Factories, antes crie uma pasta chamada "images" dentro de "storage/app/public"(Se a pasta não existir)

## API Endpoints

Em aplicações que irão consumir está API, adicione "X-Requested-With: XMLHttpRequest" aos headers

As rotas que exigem autenticação são aquelas com "$" ao lado

### Autenticação

```
POST /login - Login (se não houver usuários registrados, no momento do login, registra o primeiro usuário com o e-mail e a senha fornecidos)

BODY:
    email[varchar],
    password[varchar]


POST$ /logout - Logout (No BODY)
```

### Propriedades

```
GET /properties - Obter uma lista de todas as propriedades (URL Params: page, name, location, bedrooms, bathrooms, max_price, for_rent and accept_animals)

GET /properties/{id} - Obter uma propriedade específica ({id} é o id da propriedade)

POST$ /properties - Registrar uma nova propriedade 
BODY: 
    name[varchar], 
    price[decimal], 
    location[varchar], 
    description[text], 
    bedrooms[integer], 
    bathrooms[integer], 
    for_rent[boolean], 
    files[][Files]
    
Se for_rent = 1, então também será necessário: 
    max_tenants[integer], 
    min_contract_time[integer], 
    accept_animals[boolean]


PUT$ /properties/{id} - Atualizar dados de uma propriedade ({id} é o id da propriedade; Pode conter em seu BODY qualquer campo presente no método POST, exceto: files[][Files])

DELETE$ /properties/{id} - Deletar uma propriedade ({id} é o id da propriedade)
```
### Imagens

```
POST$ /image-add - Registrar uma nova imagem 
Body: 
    property_id[integer], 
    files[][Files]


DELETE$ /image-delete/{id} - Deletar uma imagem ({id} é o id da imagem)
```

### Usuários

```
GET$ /user - Obter uma lista de todas os usuários

GET$ /user/{id} - Obter um usuário específico ({id} é o id do usuário)

POST$ /user - Registrar um novo usuário  *apenas para superusers*
Body: 
    name[varchar], 
    email[varchar], 
    password[varchar]


PUT$ /user/{id} - Atualizar dados de um usuário *apenas para superusers* ({id} é o id do usuário; Pode conter em seu BODY qualquer campo presente no método POST)

DELETE$ /user/{id} - Deletar um usuário ({id} é o id do usuário)
```
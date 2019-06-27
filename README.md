# API para comentários em postagens

API desenvolvida para o processo seletivo da eSapiens utilizando PHP (Laravel) e PostgreSQL

# Autor

Lucas T. A. Gouvêa

## Endpoints principais

- (POST) `/api/postagens` Criar uma postagem;
- (POST) `/api/postagens/{id}/comentar` Comentar em uma postagem (controlado por throttle, máximo de 10 requisições por minuto e por uma permissão chamada `comentar-postagem`);
- (POST) `/api/comentarios/{id}/excluir` Exclusão de comentário.

- (GET) `/api/postagens/{id}/comentarios` Listar todos os comentários de uma postagem (controlado por throttle, máximo de requisições de 20 por minuto);
- (GET) `/api/comentarios_do_usuario` Listar todos os comentários de um usuário;
- (GET) `/api/notifications` Listar notificações de um usuário (as notificações expiram em 72h);

# Instalação

- Clonar o repositório
- Rodar dentro do diretório do repo: 

```bash
cp .env.example .env
docker-compose up --build -d
docker-compose exec app composer install
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan passport:install
```

O server ficará escutando na porta 8000.

# Autenticação

Rota `/api/login`
Header: `application/x-www-form-urlencoded`
Corpo do request:
```
    grant_type:password
    client_id:2
    client_secret:PTGXNTsKwf1EOE2r6TAQaU9caqlZLHufKnQSApuC
    username:admin
    password:123456
    scope:
```

# Documentação
Foi gerada uma documentação pelo Phpdox em `/docs/html`
Também há uma collection de requests para o Postman em `/docs/api-postagens.postman_collection.json`,
para usar basta importá-la no Postman, colocar o client secret gerado pelo Passport no corpo do request de login
e inicializar uma váriavel de ambiente chamada `token_sapiens` com o valor do `access_token` na resposta desse endpoint.
Todos os demais requests vão utilizar o token nessa variável.

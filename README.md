# API para comentários em postagens

API desenvolvida para o processo seletivo da eSapiens utilizando PHP (Laravel) e PostgreSQL

## Endpoints

- (POST) `/postagens` Criar uma postagem; (formato do input: **JSON**)
- (POST) `/postagens/{id}/comentar` Comentar em uma postagem;
- (POST)`/comentarios/{id}/excluir` Exclusão de comentário.

- (GET) `/postagens/{id}/comentarios` Listar todos os comentários de uma postagem;
- (GET)`/usuarios/{id}/comentarios` Listar todos os comentários de um usuário;
- (GET)`/usuarios/{id}/notificacoes` Consultar notificações de um usuário;


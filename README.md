## Pastelaria API

Instalação e configuração:

```bash
git clone git@github.com:oyamasantos/pastelaria.git
cd pastelaria/
composer install
docker compose up --build -d
```

Executar testes unitários: 

```bash
docker exec -it pastelaria-api php artisan test
```

Colletion para teste na raiz do projeto:
pastelaria.postman_collection.json 




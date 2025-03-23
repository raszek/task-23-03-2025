## Running docker

```bash
cd docker
docker compose up -d
```

## Manual tests
1. Connect to container
```bash
sudo docker compose exec app bash
```
2. Wait a while for database connection

3. In container run migrations
```
bin/console doctrine:migrations:migrate --no-interaction
```

4. Run fixtures
```
bin/console doctrine:fixtures:load
```

5. Do manual tests
```http request
### List products
GET http://localhost:8000/api/products

### View product
GET http://localhost:8000/api/products/5

### Create product
POST http://localhost:8000/api/products
Content-Type: application/json

{
"name": "Toilet",
"price": "32.32",
"categories": [
"house"
]
}

### Update product
PUT http://localhost:8000/api/products/9
Content-Type: application/json

{
"name": "Toilet",
"price": "12.12",
"categories": [
"house"
]
}

### Remove product
DELETE http://localhost:8000/api/products/9
```

## Available product categories
```
tools,
house,
children,
sport,
company,
cars,
shopping
```

## PHPUnit
1. Connect to container
```bash
sudo docker compose exec app bash
```

2. Run migrations
```bash
bin/console doctrine:database:create --env=test --if-not-exists
bin/console doctrine:migrations:migrate --env=test --no-interaction
```

3. Run tests
```bash
bin/phpunit
```

## Running docker

```bash
docker compose up -d
```

## Manual tests
1. Connect to container
```bash
sudo docker compose exec app bash
```

2. In container run migrations
```
bin/console doctrine:migrations:migrate --no-interaction
```

3. Run fixtures
```
bin/console doctrine:fixtures:load
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

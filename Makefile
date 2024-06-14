start: docker-up
stop: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up composer-install copy-config

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

composer-install:
	docker-compose exec app composer install

copy-config:
	docker-compose exec app cp .env.example .env

migrate:
	docker-compose exec app php ./yii migrate --interactive=0

test-migrate:
	docker-compose exec app php ./tests/bin/yii migrate --interactive=0

test-run:
	docker-compose exec app php ./vendor/bin/codecept run
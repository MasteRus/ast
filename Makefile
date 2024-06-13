start: docker-up
stop: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up

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

migrate:
	docker-compose exec app php ./yii migrate --interactive=0

test-migrate:
	docker-compose exec app php ./tests/bin/yii migrate --interactive=0

test-run:
	docker-compose exec app php ./vendor/bin/codecept run
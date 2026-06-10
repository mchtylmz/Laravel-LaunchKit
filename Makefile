.PHONY: test build fresh dev pint setup docker-up docker-down shell

test:
	php artisan test

build:
	npm run build

fresh:
	php artisan migrate:fresh --seed

dev:
	npm run dev

pint:
	./vendor/bin/pint

setup:
	cp .env.example .env || true
	composer install
	npm install
	php artisan key:generate
	touch database/database.sqlite
	php artisan storage:link
	php artisan migrate:fresh --seed
	npm run build

docker-up:
	docker compose up -d --build

docker-down:
	docker compose down

shell:
	docker compose exec app bash

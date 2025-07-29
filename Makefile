build:
	docker compose build --no-cache
	docker compose up -d app
	docker compose exec app composer install
	docker compose exec app npm i
	docker compose exec app php artisan key:generate
	docker compose down

run:
	docker compose up -d
	docker compose exec -T app npm run dev

fill:
	docker compose exec app php artisan migrate:fresh
	docker compose exec app php artisan db:seed

stop:
	docker compose down

bash:
	docker compose exec -it app bash

red:
	docker compose exec -it redis redis-cli -h app-redis -a root

logs:
	docker compose exec -it app tail -f storage/logs/laravel.log





roll:
	curl ascii.live/rick

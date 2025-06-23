build:
	docker compoe build
	docker compose up -d app
	docker compose exec app composer update
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

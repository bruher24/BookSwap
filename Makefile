build:
	docker compose build --no-cache
	docker compose up -d app
	docker compose exec app composer install
	docker compose exec app php artisan key:generate
	docker compose down

run:
	docker compose up -d

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

psalm:
	docker compose run --rm psalm psalm --output-format=xml | xsltproc vendor/roave/psalm-html-output/psalm-html-output.xsl - > psalm-report.html

clear:
	docker compose exec app php artisan optimize:clear
	docker compose exec app cat /dev/null > storage/logs/laravel.log
	clear

clear_photos:
	docker compose exec app find storage/app/public/avatars/ -type f -not -name 'avatar.png' -print0 | xargs -0 rm --

clear_covers:
	docker compose exec app find storage/app/public/covers/ -type f -not -name 'cover.png' -print0 | xargs -0 rm --

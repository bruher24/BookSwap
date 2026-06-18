build:
	docker compose build --no-cache
	docker compose up -d app
	docker compose exec app composer install
	docker compose exec app php artisan key:generate
	docker compose down

fill:
	docker compose exec app php artisan migrate:fresh
	docker compose exec app php artisan db:seed

logs:
	docker compose exec -it app tail -f storage/logs/laravel.log

psalm:
	docker compose exec app ./vendor/bin/psalm --no-cache --output-format=xml --force-jit | xsltproc vendor/roave/psalm-html-output/psalm-html-output.xsl - > psalm-report.html

clear:
	docker compose exec app php artisan optimize:clear
	docker compose exec app cat /dev/null > storage/logs/laravel.log
	clear

clear-photos:
	docker compose exec app find storage/app/public/avatars/ -type f -not -name 'avatar.png' -print0 | xargs -0 rm --

clear-covers:
	docker compose exec app find storage/app/public/covers/ -type f -not -name 'cover.png' -print0 | xargs -0 rm --

index-models:
	docker compose exec app php artisan scout:import 'App\Models\Author'
	docker compose exec app php artisan scout:import 'App\Models\Book'

# requires openapi-spec-validator to be installed
# pip install openapi-spec-validator
test-api:
	openapi-spec-validator --errors all --schema 3.1  resources/swagger/openapi.yaml

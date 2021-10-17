##
## Project
## ------
start: ## Install and Start project
	docker-compose up --build --remove-orphans --force-recreate --detach
	composer install

stop: ## Stop project
	docker-compose stop

kill:
	docker-compose kill
	docker-compose down --volumes --remove-orphans

clean: kill ## Stop the project and remove all generated Docker, Composer and Symfony artifacts
	rm -rf var vendor

clear-all:
	rm -rf var vendor mysql
	docker volume prune
	mkdir mysql

restart: kill start ## Restart the project from scratch

reinstall: stop clear-all start ## Reinstall the project with fresh database

##
## Frontend (yarn)
## ------
yarn-watch: ## Run yarn watch
	docker-compose run --rm todoapp-node-service yarn encore dev --watch

yarn-dev: ## Run yarn dev
	docker-compose run --rm todoapp-node-service yarn dev

yarn-install: ## Run yarn install
	docker-compose run --rm todoapp-node-service yarn install

yarn-add: ## Run yarn add to add dependencies
	docker-compose run --rm todoapp-node-service yarn add $(package)

yarn-add-dev: ## Run yarn add to add dev dependencies
	docker-compose run --rm todoapp-node-service yarn add $(package) --dev

.PHONY: start stop kill clean restart reinstall vendor yarn-watch yarn-dev yarn-add-dev yarn-install yarn-add

##
## Utilities
## -------
# todo: fix this
vendor: composer.json composer.lock
	composer install

prepare-db: ## Create database and update schema (run 'docker exec -it php74-container bash' before)
	php bin/console doctrine:database:create --if-not-exists
	php bin/console doctrine:migrations:migrate
	php bin/console --env=test doctrine:database:create --if-not-exists
	php bin/console --env=test doctrine:migrations:migrate
	php bin/console --env=test doctrine:fixtures:load

.PHONY: prepare-db

##
## Code quality control
## ----------------------
check: run-integration run-unit lint composer-validate phpmd ## Run all code quality checks

run-integration: ## Run Integration tests
	docker exec todoapp-php74-container php bin/console --env=test doctrine:fixtures:load
	docker exec todoapp-php74-container ./vendor/bin/phpunit tests/Integration

run-unit: ## Run Unit tests
	docker exec todoapp-php74-container ./vendor/bin/phpunit tests/Unit

lint: lint-yaml lint-php lint-eslint ## Validate YAML files with Symfony YAML linter, check PHP code style with PHP CS Fixer and check JS code style with ESlint

lint-yaml: ## Validate YAML files with Symfony YAML linter (https://symfony.com/doc/current/components/yaml.html#syntax-validation)
	php vendor/bin/yaml-lint config --parse-tags

lint-php: ## Check PHP code style with PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	php vendor/bin/php-cs-fixer fix src/
	php vendor/bin/php-cs-fixer fix tests/
	php vendor/bin/php-cs-fixer fix migrations/

lint-eslint: ## Check JS code style with ESlint  (https://www.jondjones.com/frontend/javascript/js-tips/how-to-add-linting-to-your-javascript-project/)
	eslint *.js assets/

phpmd: ## Parse PHP code using PHPMD (https://phpmd.org/)
	./vendor/bin/phpmd src text codesize,cleancode,unusedcode --baseline-file phpmd.baseline.xml

composer-validate: ## Validate composer.json and composer.lock using Composer's built-in validator
	composer validate

.PHONY: run-integration run-unit lint lint-yaml lint-php lint-eslint composer-validate phpmd

#
# Auxiliary recipes
# -----------------------
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-24s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m## /[33m/' && printf "\n"

.PHONY: help

.DEFAULT_GOAL := help
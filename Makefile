.PHONY: help up down build shell install test phpstan cs-fix cs-check clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

up: ## Start all Docker services
	docker compose up -d

down: ## Stop all Docker services
	docker compose down

build: ## Build Docker images
	docker compose build

shell: ## Open a shell in the PHP container
	docker compose exec php bash

install: ## Install Composer dependencies
	docker compose exec php composer install

update: ## Update Composer dependencies
	docker compose exec php composer update

test: ## Run PHPUnit tests
	docker compose exec php vendor/bin/phpunit

test-unit: ## Run only unit tests
	docker compose exec php vendor/bin/phpunit tests/Unit

test-integration: ## Run only integration tests
	docker compose exec php vendor/bin/phpunit tests/Integration

phpstan: ## Run PHPStan static analysis
	docker compose exec php vendor/bin/phpstan analyse --no-progress

cs-fix: ## Fix code style with PHP-CS-Fixer
	docker compose exec php vendor/bin/php-cs-fixer fix

cs-check: ## Check code style without fixing
	docker compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff

clean: ## Clean up containers, volumes, and caches
	docker compose down -v
	rm -rf vendor/
	rm -f .php-cs-fixer.cache .phpunit.result.cache

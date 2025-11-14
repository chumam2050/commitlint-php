.PHONY: help install test coverage clean switch-php

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install composer dependencies
	composer install

test: ## Run tests
	composer test

coverage: ## Run tests with coverage report
	composer test:coverage

clean: ## Clean vendor and cache files
	rm -rf vendor/
	rm -f composer.lock
	rm -rf coverage/
	rm -f .phpunit.result.cache

switch-php: ## Switch PHP version (usage: make switch-php VERSION=7.4)
ifndef VERSION
	@echo "Error: VERSION not specified"
	@echo "Usage: make switch-php VERSION=7.4"
	@echo "Supported versions: 7.3, 7.4, 8.0, 8.1, 8.2, 8.3"
	@exit 1
endif
	@./switch-php-version.sh $(VERSION)

# Aliases
deps: install ## Alias for install
build: install ## Build (install dependencies)

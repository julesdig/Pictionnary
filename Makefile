
S3_BUCKET_URL=http://localhost:4566/local-bucket
.DEFAULT_GOAL := help
.PHONY : help
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
create-bucket: ## Create an S3 bucket
	curl -X PUT "$(S3_BUCKET_URL)"

upload-file: ## Upload a file to S3
	curl -X PUT --upload-file $(file) "$(S3_BUCKET_URL)/$(file)"

list-files: ## List files in S3
	curl "$(S3_BUCKET_URL)/"

s3-init: create-bucket upload-file list-files

init-docker: ## Initialize Docker containers
	docker compose up -d --build
	@echo "✅ Docker containers initialized successfully!"
composer-install: ## Install Composer dependencies
	docker exec -it pictionary_php composer install
	@echo "✅ Composer dependencies installed successfully!"

create-db: ## Create the database
	docker exec -it pictionary_php php bin/console doctrine:database:create --if-not-exists
	@echo "✅ Database created successfully!"

update-db: ## Update the database schema
	docker exec -it pictionary_php php bin/console doctrine:schema:update --force
	@echo "✅ Database schema updated successfully!"
init-project: ## Initialize project (Docker + DB setup + Composer install)
	$(MAKE) init-docker
	$(MAKE) composer-install
	$(MAKE) create-db
	$(MAKE) update-db
	@echo "✅ Project initialized successfully!"

phpcs: ## play phpcs
	docker exec -it pictionary_php php bin/phpcs

phpcbf: ## play phpcbf
	docker exec -it pictionary_php php bin/phpcbf

phpmd: ## play phpcs
	docker exec -it pictionary_php php bin/phpmd  src/ text phpmd.xml

phpstan: ## play phpcs
	docker exec -it pictionary_php php bin/phpstan analyse src

coding-standards: ## Check coding standards
	$(MAKE) phpcbf
	$(MAKE) phpcs
	$(MAKE) phpmd
	$(MAKE) phpstan
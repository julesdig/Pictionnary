
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

init-project: ## Initialize project (Docker + DB setup + Composer install)
	docker compose up -d --build
	docker exec -it meet-u_php php bin/console composer install
	docker exec -it meet-u_php php bin/console doctrine:database:create --if-not-exists
	docker exec -it meet-u_php php bin/console doctrine:schema:update --force
	@echo "✅ Project initialized successfully!"
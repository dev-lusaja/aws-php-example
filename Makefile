.DEFAULT_GOAL := help

IMAGE_NAME = aws-sdk-php

build: ## Build the docker image
	@docker-compose -f docker-compose.build.yml build
	@docker run -ti --rm -v $(PWD)/app:/app $(IMAGE_NAME) composer update

php: ## execute the code
	@docker run -ti --rm -v $(PWD)/app:/app $(IMAGE_NAME) php ${FILE}

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'
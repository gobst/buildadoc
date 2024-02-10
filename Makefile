DOCKER_COMPOSE = docker-compose -f docker-compose.yml -p buildadoc

include Makefile.dev.mk

.PHONY: start
start:
	${DOCKER_COMPOSE} up -d buildadoc

.PHONY: build
build:
	${DOCKER_COMPOSE} up -d buildadoc --build

.PHONY: clear
clear:
	${DOCKER_COMPOSE} down -v

.PHONY: bash
bash:
	${DOCKER_COMPOSE} run --rm --no-deps buildadoc /bin/bash
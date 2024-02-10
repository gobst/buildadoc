DOCKER_COMPOSE_DEV = docker-compose -f docker-compose.yml -p buildadoc-dev

.PHONY: start-dev
start-dev:
	${DOCKER_COMPOSE_DEV} up -d buildadoc-dev

.PHONY: stop-dev
stop-dev:
	${DOCKER_COMPOSE_DEV} stop -d buildadoc-dev

.PHONY: build-dev
build-dev: build
build-dev:
	${DOCKER_COMPOSE_DEV} up -d buildadoc-dev --build

.PHONY: clear-dev
clear-dev:
	${DOCKER_COMPOSE_DEV} down -v

.PHONY: bash-dev
bash-dev:
	${DOCKER_COMPOSE_DEV} run --rm --no-deps buildadoc-dev /bin/bash

.PHONY: run-docker-code-check
run-docker-code-check: build-dev
run-docker-code-check:
	${DOCKER_COMPOSE_DEV} exec -it buildadoc-dev bin/checkCode.sh

.PHONY: run-code-check
run-code-check:
	bin/checkCode.sh

.PHONY: run-docker-unit-tests
run-docker-unit-tests: build-dev
run-docker-unit-tests:
	${DOCKER_COMPOSE_DEV} exec -it buildadoc-dev bin/phpunit tests/unit

.PHONY: run-unit-tests
run-unit-tests:
	bin/phpunit tests/unit --display-warnings

.PHONY: unit-tests-coverage-html
unit-tests-coverage-html:
	bin/phpunit tests/unit --coverage-html=bin/output/unit/coverage --coverage-html bin/output/unit/coverage

.PHONY: unit-tests-coverage-text
unit-tests-coverage-text:
	bin/phpunit tests/unit --coverage-html=bin/output/unit/coverage --coverage-text

.PHONY: run-infection
run-infection:
	bin/infection --threads=4
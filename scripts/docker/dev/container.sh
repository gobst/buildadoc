#!/bin/bash

DOCKER_COMPOSE="docker-compose -f ../../../res/docker/config/docker-compose.yml -p buildadoc"

start() {
    ${DOCKER_COMPOSE} up -d buildadoc-dev
}

stop() {
    ${DOCKER_COMPOSE} stop -d buildadoc-dev
}

build() {
    ${DOCKER_COMPOSE} up -d buildadoc-dev --build
}

clear() {
    ${DOCKER_COMPOSE} down -v
}

bash() {
    ${DOCKER_COMPOSE} run --rm --no-deps buildadoc-dev /bin/bash
}

if [ $# -eq 0 ]; then
    echo "Missing command."
    exit 1
fi

case "$1" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    build)
        build
        ;;
    clear)
        clear
        ;;
    bash)
        bash
        ;;
    *)
        echo "Invalid command: $1"
        exit 1
        ;;
esac
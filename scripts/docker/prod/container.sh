#!/bin/bash

DOCKER_COMPOSE="docker-compose -f ../../../res/docker/config/docker-compose.yml -p buildadoc"

start() {
    ${DOCKER_COMPOSE} up -d buildadoc
}

build() {
    ${DOCKER_COMPOSE} up -d buildadoc --build
}

clear() {
    ${DOCKER_COMPOSE} down -v
}

bash() {
    ${DOCKER_COMPOSE} run --rm --no-deps buildadoc /bin/bash
}

if [ $# -eq 0 ]; then
    echo "Command is missing."
    exit 1
fi

case "$1" in
    start)
        start
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
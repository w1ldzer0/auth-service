DOCKER_COMPOSE = docker-compose -f docker-compose.yml
DOCKER_COMPOSE_PHP_FPM_EXEC = ${DOCKER_COMPOSE} exec -u www-data php

up: #Setting up project on DEV
	${DOCKER_COMPOSE} up -d
	${DOCKER_COMPOSE} run composer install --ignore-platform-req=ext-intl
	${DOCKER_COMPOSE_PHP_FPM_EXEC} php bin/console doctrine:migrations:migrate

down: #Stops projects containers
	${DOCKER_COMPOSE} down

cs-fixer-view: Shows not formated files
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/php-cs-fixer  fix --dry-run --diff $(ARGS)

cs-fixer-fix: #ormates files
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/php-cs-fixer  fix $(ARGS)

psalm: #Runs analyzing code
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/psalm $(ARGS)

unittest: #Runs unit tests
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/phpunit

ifneq ($(MAKECMDGOALS),clean)
-include .env
endif

# This should only be called once for the initial setup
first-install: check-env-file certificates fix-run-permissions install

# Install everything except composer dependencies
install: check-env-file build-app run message-okay

# Install everything including composer dependencies
install-with-composer: check-env-file build-app run composer-install message-okay

# Generate self-signed certificates for local environment
certificates: generate-certificates fix-cert-permissions

# Build image separately to use a better caching algorithm
build-app:
	@docker build -t ${PROJECT_NAME} docker > /dev/null 2>&1 </dev/null

# Open new ssh session into the container
ssh:
	@docker exec -it -u www ${PROJECT_NAME} /bin/sh

# Run the container
run:
	@docker compose up -d --build > /dev/null 2>&1 </dev/null

# Remove the container and image
destroy:
	@docker compose down --rmi=all

# Create the default network based on the application name
network-create:
	@docker network create ${PROJECT_NAME}

# Enable xDebug
xdebug-on:
	@./docker/run.sh "/opt/xdebug.sh on" true

# Disable xDebug
xdebug-off:
	@./docker/run.sh "/opt/xdebug.sh off" true

# Install composer dependencies
composer-install:
	@./docker/run.sh "composer install" > /dev/null 2>&1 </dev/null

# Install composer dependencies without "require-dev"
composer-install-prod:
	@./docker/run.sh "composer install --no-dev"

# Update all composer dependencies
composer-update:
	@./docker/run.sh "composer update"

# Print "okay" message
message-okay:
	@echo "Setup was successful. You may now want to call https://localhost in your browser."

########################################################
# Use the commands below only if you know what they do #
########################################################
generate-certificates:
	@openssl req -new -newkey rsa:4096 -days 3650 -nodes -x509 -subj "/C=DE/ST=BY/L=M/O=${PROJECT_NAME}/CN=${PROJECT_NAME}.local" -keyout ./docker/rootfs/etc/nginx/ssl/privkey.pem -out ./docker/rootfs/etc/nginx/ssl/fullchain.pem > /dev/null 2>&1 </dev/null

fix-cert-permissions:
	@chmod +r ./docker/rootfs/etc/nginx/ssl/*

fix-run-permissions:
	@chmod +x docker/run.sh

check-env-file:
	@if [ ! -f ".env" ] ; then echo "Error: You first have to copy the .env.dist and adjust the project name to your needs."; exit 2; fi

create-env-file:
	@if [ ! -f ".env" ] ; then cp .env.dist .env; fi

env:
	@set -a; . ./.env; set +a

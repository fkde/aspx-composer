#!/bin/bash

set -a; . ./.env; set +a

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
APP_DIR=$(readlink -f "$DIR/../app")

if [ -z "$1" ]; then
  echo "You have to supply at least one argument."
  exit 0
fi;

function runWithUser() {
  docker run \
    --rm \
    -u www \
    -v "$APP_DIR":/var/www \
    "$PROJECT_NAME" \
    sh -c "$1"
}

function runWithAdminInContainer() {
  docker exec "$PROJECT_NAME" $1
}

if [ "$2" == "true" ]; then
  echo "Warning! Running command directly in the current container."
  runWithAdminInContainer "$1"
  exit 0
fi

runWithUser "$1"
exit 0
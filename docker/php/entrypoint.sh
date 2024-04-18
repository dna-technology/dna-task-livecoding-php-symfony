#! /bin/sh

setup_db () {
  php bin/console doctrine:database:create --env="$1" --if-not-exists
  php bin/console doctrine:schema:update --env="$1" --force
}

if [ "$*" = "symfony server:start" ]; then
  until nc -z database 5432; do
    sleep 1
  done

  setup_db test
  setup_db dev
fi

exec "$@"

#!/usr/bin/env sh
docker-compose --env-file .env.local up -d
docker exec -it ttq_rabbitmq rabbitmqctl import_definitions /etc/rabbitmq/definitions.json
docker exec -it ttq_app php bin/console doctrine:schema:create -q

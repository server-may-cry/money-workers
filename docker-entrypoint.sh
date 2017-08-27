#!/bin/sh
wait-for-it/wait-for-it.sh redis:6379
wait-for-it/wait-for-it.sh rabbitmq:4369

exec "$@"

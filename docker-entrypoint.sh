#!/bin/sh
wait-for-it/wait-for-it.sh redis:6379
wait-for-it/wait-for-it.sh rabbitmq:4369
# rabbitmq listen socket and not ready :(
sleep 5s

exec "$@"

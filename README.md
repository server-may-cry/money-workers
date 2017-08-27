money-workers
=============
[![Build Status](https://travis-ci.org/server-may-cry/money-workers.svg?branch=master)](https://travis-ci.org/server-may-cry/money-workers)

###### Stack:
* php 7.1
* symfony 3.3
* symfony/flex
* redis
* rabbitmq

run phpstan and unit tests: `$ make test`

run on live (need swarm): `$ docker stack deploy money-workers --compose-file docker-compose-live.yml`

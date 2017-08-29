money-workers
=============
[![Build Status](https://travis-ci.org/server-may-cry/money-workers.svg?branch=master)](https://travis-ci.org/server-may-cry/money-workers)

#### Stack:
* php 7.1
* symfony 3.3
* symfony/flex
* redis
* rabbitmq

#### Run:
* phpstan and unit tests: `$ make test`
* in swarm: `$ docker stack deploy money-workers --compose-file docker-compose-live.yml`
* by docker-compose (no more 1 replica per service): `$ docker-compose -f docker-compose-live.yml up`

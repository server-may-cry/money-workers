money-workers
=============
[![Build Status](https://travis-ci.org/server-may-cry/money-workers.svg?branch=master)](https://travis-ci.org/server-may-cry/money-workers)

###### Stack:
* symfony 3.3
* symfony/flex
* redis
* rabbitmq

run phpstan and unit tests: `$ docker-compose run --no-deps php make test`

run on live (need swarm: `$ docker swarm init`): `$ test.sh`

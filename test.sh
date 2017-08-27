git submodule init
git submodule update

docker stack deploy money-workers --compose-file docker-compose-live.yml

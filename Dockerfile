FROM php:7.1-alpine

WORKDIR /app

RUN apk update && apk add \
  bash \
  make \
  && docker-php-ext-install \
  bcmath

CMD ["sh"]

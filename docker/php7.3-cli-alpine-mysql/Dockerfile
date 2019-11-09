FROM php:7.3-cli-alpine

RUN apk add libxml2-dev
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install dom simplexml

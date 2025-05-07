FROM mysql

RUN apt-get update \
     && docker-php-ext-install mysqli pdo pdo_mysql \
     && docker-php-ext-enable pdo_mysql
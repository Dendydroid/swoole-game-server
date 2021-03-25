FROM ubuntu:20.04

ENV DEBIAN_FRONTEND noninteractive

WORKDIR /var/www

RUN apt-get update && apt-get install -y curl && cd ~ && curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh && \
    chmod +x ~/nodesource_setup.sh && ~/nodesource_setup.sh && apt-get update && apt-get install -y nodejs \
    && npm install -g nodemon typescript

RUN apt-get update && apt-get install -y openssl vim libcurl3-openssl-dev php-cli unzip ca-certificates

RUN cd ~ && curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    HASH=`curl -sS https://composer.github.io/installer.sig` && echo $HASH && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer

EXPOSE 443

CMD npm install && npm run dev
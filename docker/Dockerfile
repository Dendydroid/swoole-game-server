FROM ubuntu:20.04

ENV DEBIAN_FRONTEND noninteractive

WORKDIR /var/www

RUN apt-get update && apt install -y software-properties-common && add-apt-repository ppa:ondrej/php && \
    apt-get update && apt-get install -y php8.0 php-pear vim php-dev libcurl3-openssl-dev php-cli php8.0-{bz2,curl,intl,mysql,mysqli,pgsql,fileinfo,readline,xml,gd,gettext,mbstring,exif,zip} unzip && \
    pecl install swoole

RUN echo -e '\n extension=swoole.so' >> /etc/php/8.0/cli/php.ini

RUN cd ~ && curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh && \
    chmod +x ~/nodesource_setup.sh && ~/nodesource_setup.sh && apt-get update && apt-get install -y nodejs lsof \
    && npm install -g nodemon

COPY ./config/nodemon.json /var/www/nodemon.json

RUN cd ~ && curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    HASH=`curl -sS https://composer.github.io/installer.sig` && echo $HASH && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer

EXPOSE 8443

CMD npm start
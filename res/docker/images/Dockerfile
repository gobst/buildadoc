############################################################################
#
# This file is part of BuildADoc.
#
# (c) Guido Obst
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
############################################################################

FROM debian:bullseye-slim

LABEL maintainer="Guido Obst" \
	  description="BuildADoc production image" \
	  version="1.0.0"

WORKDIR /var/www/html/BuildADoc

# install misc tools
RUN apt-get  \update && \
    apt-get install -y wget \
    gnupg \
    git \
    unzip \
    nano \
    htop \
    apache2 \
    aptitude \
   	apt-transport-https \
  	ca-certificates \
    curl \
    gnupg \
    lsb-release \
    make \
    && apt-get clean

# add php resource
COPY ../config/php/php.list /etc/apt/sources.list.d/php.list
RUN wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add

# install php
RUN aptitude update && \
    aptitude install -y php8.3-cli php8.3-curl php8.3-fpm php8.3-json php8.3-bcmath php8.3-zip php8.3-memcached \
    					php8.3-mbstring php8.3-xml php8.3-xmlrpc php8.3-gd php8.3-apcu

# set timezone
RUN ln -fs /usr/share/zoneinfo/Europe/Berlin /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

# install composer
RUN cd /home
RUN wget -O composer-setup.php https://getcomposer.org/installer
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer
RUN rm -R composer-setup.php

# create folder
RUN mkdir /home/files \
    /home/files/config \
    /home/files/scripts \
    bin \
    res \
    cfg \
    src

# copy files
COPY ../../../res/docker/config /home/files/config
COPY ../../../src src
COPY ../../../res res
COPY ../../../cfg/services.yml cfg
COPY ../../../composer.json composer.json

# run composer
RUN composer install --no-dev

EXPOSE 8080

CMD tail -f /dev/null

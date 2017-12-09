FROM amazonlinux:latest

RUN yum -y update && yum -y install \
    php70 \
    php70-phar \
    php70-pecl-apcu \
    git \
    openssh \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/bin/ && composer self-update

WORKDIR /app/
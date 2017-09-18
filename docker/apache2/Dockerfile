FROM ubuntu:16.04

RUN apt-get update && \
    apt-get install -y apache2 \
    php libapache2-mod-php php-mcrypt php-mysql && \
    a2enmod rewrite

COPY default /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]

FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/

<<<<<<< HEAD
EXPOSE 80
=======
EXPOSE 80
>>>>>>> 851349730b18e24f55ddb80f02f07d24e7418cc2

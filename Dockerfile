FROM ubuntu:xenial

RUN apt-get update

RUN apt-get -y upgrade

RUN echo 'mysql-server mysql-server/root_password password password' |debconf-set-selections
RUN echo 'mysql-server mysql-server/root_password_again password password' |debconf-set-selections
RUN apt -y install apache2 php php-mysql mysql-server wget less vim git
RUN cd /var/www/; wget https://wordpress.org/latest.tar.gz ;tar xzf latest.tar.gz
RUN git clone https://github.com/magwas/ep.git; mysql -ppassword -u root <ep/setupdb.sql


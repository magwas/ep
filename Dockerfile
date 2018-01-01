FROM ubuntu:xenial

RUN apt-get update

RUN apt-get -y upgrade

RUN echo 'mysql-server mysql-server/root_password password password' |debconf-set-selections
RUN echo 'mysql-server mysql-server/root_password_again password password' |debconf-set-selections
RUN apt -y install apache2 php php-mysql mysql-server wget less vim git php-dom subversion php-xdebug make
RUN service mysql start;git clone https://github.com/magwas/ep.git; mysql -ppassword -u root <ep/setupdb.sql
RUN wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -O /usr/local/bin/wp
RUN chmod +x /usr/local/bin/wp
RUN wget https://phar.phpunit.de/phpunit.phar -O /usr/local/bin/phpunit
RUN chmod +x /usr/local/bin/phpunit
RUN wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar -O /usr/local/bin/phpcs
RUN chmod +x /usr/local/bin/phpcs
RUN wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar -O /usr/local/bin/phpcbf
RUN chmod +x /usr/local/bin/phpcbf
RUN wp --allow-root core download --path=/var/www/wordpress
RUN mv /var/www/wordpress/wp-config-sample.php /var/www/wordpress/wp-config.php
RUN service mysql start;wp --allow-root --path=/var/www/wordpress core install --url=http://localhost/ --title=test --admin_user=root --admin_email=root@localhost.local
RUN ln -s /ep /var/www/wordpress/wp-content/plugins
RUN apt -y install subversion
RUN git clone -b master https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards.git /usr/local/share/wpcs
RUN phpcs --config-set installed_paths /usr/local/share/wpcs
RUN service mysql start;wp --allow-root --path=/var/www/wordpress scaffold plugin-tests ep;cd /ep;bin/install-wp-tests.sh test_database root password





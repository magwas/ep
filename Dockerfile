FROM ubuntu:xenial
RUN apt-get update
RUN apt-get -y upgrade
RUN echo 'mysql-server mysql-server/root_password password password' |debconf-set-selections
RUN echo 'mysql-server mysql-server/root_password_again password password' |debconf-set-selections
RUN apt -y install apache2 php php-mysql mysql-server wget less vim git php-dom subversion\
    php-xdebug make composer unzip zip syslog-ng-core firefox vnc4server net-tools telnet\
    libapache2-mod-php php-curl php-mbstring php-zip openjdk-8-jdk fvwm
RUN wget  https://deb.nodesource.com/setup_8.x -O - | bash -
RUN apt-get -y install nodejs
RUN git clone -b master https://github.com/magwas/ep.git
RUN service mysql start; mysql -ppassword -u root <ep/setupdb.sql
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
RUN git clone -b live https://github.com/edemo/wp_oauth_plugin.git /usr/local/wp_PDOauth_plugin
RUN ln -s /usr/local/wp_PDOauth_plugin/eDemo-SSOauth /tmp/wordpress/wp-content/plugins/eDemo-SSOauth
RUN rm -f /var/run/mysqld/mysqld.pid
RUN cd /ep ; npm install
RUN cd /ep ; mv node_modules /usr/local/lib
RUN npm -g install mocha
RUN wget http://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar -O /usr/local/lib/selenium-server-standalone.jar
RUN composer require --dev phpunit/phpunit
RUN composer require --dev phpunit/phpunit-selenium
RUN a2enmod rewrite
RUN wget https://github.com/mozilla/geckodriver/releases/download/v0.19.1/geckodriver-v0.19.1-linux64.tar.gz
RUN tar xzf geckodriver-v0.19.1-linux64.tar.gz
RUN mv geckodriver /usr/local/bin
RUN rm geckodriver-v0.19.1-linux64.tar.gz
RUN cd /ep;composer install
RUN mv /ep/vendor /usr/local/lib




all: stylecheck phptests itests


stylecheck:
	phpcs
phptests: 
	phpunit --whitelist 'ep' --coverage-html coverage-unit --testsuite unit

/var/run/mysqld/mysqld.pid:
	service mysql start
itests: /var/run/mysqld/mysqld.pid
	phpunit --whitelist 'ep' --coverage-html coverage-integration --bootstrap integrationtests/bootstrap.php --testsuite integration

testenv:
	docker run -v $$(pwd):/ep -w /ep -it magwas/ep /bin/bash


all: stylecheck phptests itests

shippable:
	mkdir -p shippable/testresults
	mkdir -p shippable/codecoverage

stylecheck:
	phpcs

phptests: shippable
	phpunit --whitelist 'ep' --log-junit shippable/testresults/unit.xml --coverage-html shippable/codecoverage/coverage-unit --coverage-xml shippable/codecoverage/coverage-unit --testsuite unit

/var/run/mysqld/mysqld.pid:
	service mysql start

itests: shippable /var/run/mysqld/mysqld.pid
	phpunit --whitelist 'ep' --log-junit shippable/testresults/integration.xml --coverage-html shippable/codecoverage/coverage-integration --coverage-xml shippable/codecoverage/coverage-integration --bootstrap integrationtests/bootstrap.php --testsuite integration

testenv:
	docker run -v $$(pwd):/ep -w /ep -it magwas/ep /bin/bash

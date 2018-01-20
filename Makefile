
all: e2e

shippable:
	mkdir -p shippable/testresults
	mkdir -p shippable/codecoverage

stylecheck:
	/usr/local/bin/phpcs

phptests: shippable
	phpunit --whitelist 'ep' --log-junit shippable/testresults/unit.xml --coverage-html shippable/codecoverage/coverage-unit --coverage-xml shippable/codecoverage/coverage-unit --testsuite unit

e2e: wptestsetup
	phpunit --whitelist 'ep' --log-junit shippable/testresults/e2e.xml --coverage-html shippable/codecoverage/coverage-e2e --coverage-xml shippable/codecoverage/coverage-e2e --testsuite e2e

/var/run/mysqld/mysqld.pid:
	service mysql start

itests: shippable /var/run/mysqld/mysqld.pid
	phpunit --whitelist 'ep' --log-junit shippable/testresults/integration.xml --coverage-html shippable/codecoverage/coverage-integration --coverage-xml shippable/codecoverage/coverage-integration --bootstrap integrationtests/bootstrap.php --testsuite integration

testenv:
	docker run --rm -p 5900:5900 -p 80:80 -v $$(pwd):/ep -w /ep -it magwas/ep /bin/bash

node_modules:
	ln -sf /usr/local/lib/node_modules .

jsbuild: node_modules
	rm -rf dist ; npm run build

jstest: node_modules
	mocha -b --require babel-core/register es6tests

deliver: shippable jsbuild stylecheck phptests itests jstest
	zip -r shippable/ep.zip ep

wpsetup: ossetup
wptestsetup: /var/run/mysqld/mysqld.pid deliver ossetup
	tools/wpconfig

ossetup: /var/run/mysqld/mysqld.pid
	cp etc/server.* /etc/ssl
	cp etc/apache2.conf /etc/apache2
	cp etc/000-default.conf /etc/apache2/sites-available
	cp etc/syslog-ng.conf /etc/syslog-ng
	service syslog-ng restart
	service apache2 restart

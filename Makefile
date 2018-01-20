
all: deliver

shippable:
	mkdir -p shippable/testresults
	mkdir -p shippable/codecoverage

stylecheck:
	/usr/local/bin/phpcs

phptests: shippable
	phpunit --whitelist 'ep' --log-junit shippable/testresults/unit.xml --coverage-html shippable/codecoverage/coverage-unit --coverage-xml shippable/codecoverage/coverage-unit --testsuite unit

/var/run/mysqld/mysqld.pid:
	service mysql start

itests: shippable /var/run/mysqld/mysqld.pid
	phpunit --whitelist 'ep' --log-junit shippable/testresults/integration.xml --coverage-html shippable/codecoverage/coverage-integration --coverage-xml shippable/codecoverage/coverage-integration --bootstrap integrationtests/bootstrap.php --testsuite integration

testenv:
	docker run -v $$(pwd):/ep -w /ep -it magwas/ep /bin/bash

node_modules:
	ln -sf /usr/local/lib/node_modules .

jsbuild: node_modules
	rm -rf dist ; npm run build

jstest: node_modules
	mocha -b --require babel-core/register es6tests

deliver: shippable jsbuild stylecheck phptests itests jstest
	zip -r shippable/ep.zip ep


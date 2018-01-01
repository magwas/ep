
all: phptests


phptests: 
	phpunit --whitelist 'ep' --coverage-html coverage


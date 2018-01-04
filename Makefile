
all: phptests


phptests: 
	phpunit --whitelist 'ep' --coverage-html coverage

testenv:
	docker run -v $$(pwd):/ep -w /ep -it eptest /bin/bash

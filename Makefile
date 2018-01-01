
all: phptests


phptests: ep/Dashboard.php DashboardTest.php
	phpunit --whitelist 'ep' --coverage-html coverage .


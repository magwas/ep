
all: phptests


phptests: ep/Dashboard.php DashboardTest.php
	phpunit --whitelist ep/Dashboard.php --coverage-html coverage DashboardTest.php


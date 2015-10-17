#!/bin/bash

php ./Tests/app/console.php doctrine:schema:drop --force
php ./Tests/app/console.php doctrine:schema:create
php ./Tests/app/console.php doctrine:fixtures:load --fixtures="Tests/DataFixtures/Standard" -n -vvv

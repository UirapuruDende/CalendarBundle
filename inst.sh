#!/bin/bash

rm -rf vendor
rm -rf composer.lock
composer clearcache
composer install

./console.sh doctrine:fixtures:load -n

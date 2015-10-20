# CalendarBundle

A [Symfony 2](http://symfony.com) bundle for integration of [Dende/Calendar](https://github.com/UirapuruDende/Calendar) component

[![Build Status](https://travis-ci.org/UirapuruDende/CalendarBundle.svg?branch=master)](https://travis-ci.org/UirapuruDende/CalendarBundle)

## installation:

1. install via composer

    composer require dende/calendar-bundle

2. enable bundle in AppKernel

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Dende\CalendarBundle\DendeCalendarBundle(),
            ...
        );
    }
}
```

## creating test database in mysql:

    mysql -u user -ppassword -e "CREATE DATABASE `calendar_bundle_test`;"
    
## creating schema:

    cd calendar-bundle/
    ./console doctrine:schema:create
    
## loading test fixtures:

    cd calendar-bundle/
    ./console doctrine:fixtures:load --fixtures="Tests/DataFixtures/Standard"
    
## resetting db:

    cd calendar-bundle/
    ./reset-db.sh
    
## running localhost server:

    cd calendar-bundle/
    php -S localhost:8080 -t Tests/app/

## visiting page of calendar (example):
    
    http://localhost:8080/web.php/api/calendar
    
## todo list

 - [x] doctrine entity mapping
 - [x] configured test application
 - [x] REST/HATEOAS api for frontend
 - [ ] registering application services
 - [ ] frontend view based on javascript library
 - [ ] automatically registering view entity manager
 - [ ] automatically adding mapping for default entity manager
 - [ ] using sql/mongo by switching it in bundle config
 - [ ] full documentation  
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

## install assets:

    ./console.sh assets:install Tests/app
    
## running localhost server:

    cd calendar-bundle/
    php -S localhost:8080 -t Tests/app/

## visiting page of calendar (example):
    
    http://localhost:8080/web.php/api/calendar
    
## todo list

 - [x] doctrine entity mapping
 - [x] configured test application (Tests/app)
 - [ ] REST/HATEOAS api for frontend (entry point: /api/calendar/)
    - [ ] tests
    - [x] GET methods
    - [ ] filtering by date, weeks, calendar, event
    - [ ] POST methods
    - [ ] PUT methods
    - [ ] forms
 - [ ] php wrapper for fullcalendar configuration
 - [ ] automatically registering view entity manager
 - [ ] automatically adding mapping for default entity manager
 - [ ] autoconfiguration (as less instalation needed and configuring as needed)
 - [x] registering application services
 - [ ] utilize some datetimepicker for event forms
 - [ ] frontend view based on javascript library
 - [ ] using sql/mongo by switching it in bundle config
 - [ ] printing/saving to pdf
 - [ ] full documentation
 - [ ] filtering in view by calendars
 
## bugs

 - adding new event from frontend creates only single occurrence
    
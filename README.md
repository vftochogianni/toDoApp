# ToDoApp

This is a software web application in which people can add tasks to complete.

**`Disclaimer`** This project's UI has been inspired
from [Sander Cokart](https://www.youtube.com/channel/UCzwh2XtYJNDvE1aZfw9vKmg)'s [youtube series](https://www.youtube.com/watch?v=qYgf9v3PNu8&list=PLKgdkWe819ixRoJMWqQpebmUkqKaiTMBl&index=2)

## Local environment

This project is set up in docker. 
The use of Makefile makes setting up the local environment as easy as 1,2,3!

### Prerequisites
* Composer
* Docker

### Set up
Open a terminal and got to the projects folder and run the following command to set up the local environment:

```bash
make start
```

Visit [localhost:8080](localhost:8080) to get access to the web application. 

If you want to restart the local environment run the following:
```bash
make restart
```

If you want to clean up reasources and shut down the local environment run:
```bash
make clean
```
For a totally fresh start run the following in a terminal:
```bash
make reinstall
docker exec -it todoapp-php74-container bash
make prepare-db
```

#### Mysql set up
To connect to the local database, use the following connection details:

* **Host:** localhost
* **User:** root
* **Password:** secret
* **Port:** 4306

### Database
#### Entity

To create an entity follow the symfony documentation on [creating an entity class](https://symfony.com/doc/current/doctrine.html#creating-an-entity-class).
The result of those steps will be an entity and a repository that can be further extended.  

#### Migrations
After the entity is ready in a terminal run the following:
```bash
docker exec -it todoapp-php74-container bash
```
You will then be connected in the php container where you can execute the migration as such:
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
## Domain events
In the system it is only natural that domain event will be emitted to the platform. 
All domain events need to extend the interface `DomainEvent`. 

If the domain events need to be stored in the database then the only thing that needs to be done is the following.
In the `SystemEventSubscriber` add the subscription to the new domain event as such:

```php
public static function getSubscribedEvents()
    {
        return [
            // other events 
            EventClass::class => 'onDomainEvent',
        ];
    }
```
Note that the function that will handle the event is `onDomainEvent`

## Testing

Ensure that the test database is set up for the integration and e2e tests 
```bash
docker exec -it todoapp-php74-container bash
php bin/console --env=test doctrine:database:create --if-not-exists
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load
```

To run the unit tests execute:
```bash
make run-unit
```

To run the integration tests execute:
```bash
make run-integration
```

## Development
To access your local enviroment and the app visit: [http://localhost:8081/](http://localhost:8081/)

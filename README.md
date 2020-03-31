# oat-test
Test work for OAT


```
git clone git@github.com:seliverro/oat-test.git

cd oat-test

cd docker

docker-compose up
```

## Compose

### PHP (PHP-FPM)

Composer is included

```
docker-compose run php-fpm composer 
```

### Webserver (Nginx)

...

### How it works

```
Go http://localhost:81/questions/{lang} in browser for retrieving translated questions

Send post request to http://localhost:81/questions to add question 
```
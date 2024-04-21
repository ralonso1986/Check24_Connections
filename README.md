## Test study for Connections in Check24 

### Requirements

- Composer
- PHP 8.1 or more recent version
- Symfony CLI
- PHPUnit

### Setup

Install project dependencies

```bash
$ composer install
```

Run tests

```bash
$ ./vendor/bin/phpunit
```

Serve project

```bash
$ symfony serve
```

### How to use

Send a POST request to [http://localhost:8000/generateInsuranceCarrierRequestData](http://localhost:8000/generateInsuranceCarrierRequestData) including the input data within the request body as JSON. As a response the needed XML file to perform requests to the target insurance carrier is returned.

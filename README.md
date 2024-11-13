## For running the application execute:

To start the application, use the following Docker command:

```bash
docker-compose up -d --build
```

Afterwards, open a web browser and navigate to:

```
http://localhost:8080
```

## Endpoints:

### GET /
- Returns the list of people.
- Example response:
  ```json
  {"data": [{"name": "Tom"}]}
  ```

### POST /
- Adds a new person.
- Example request:
  ```json
  {
      "name": "John"
  }
  ```

### GET /find?name=Tom
- Finds a person by name.
- Example response:
  ```json
  {"data": [{"name": "Tom"}]}
  ```

## For adding a new endpoint:

To add a new endpoint to the application:

1. Add a new controller in `src/Controller`.
2. Define the route in `index.php` like so:
    - Format:
      ```php
      $router->addRoute('METHOD', 'path', Controller::class, 'controller method');
      ```
    - Example:
      ```php
      $router->addRoute('GET', '/test/new/endpoint', Http\Controller\NewController::class, 'test');
      ```

## For running code analysis execute:

To ensure code quality and consistency, run the following analysis tools:

- **PHP CS Fixer**:
  ```bash
  vendor/bin/php-cs-fixer src
  ```

- **PHPStan**:
  ```bash
  vendor/bin/phpstan analyse src --level=4
  ```

- **SonarCloud**:
    - Integrated with GitHub, automatically executed on every push.

## For running unit tests execute:

To run unit tests, use the following command:

```bash
vendor/bin/phpunit tests
```


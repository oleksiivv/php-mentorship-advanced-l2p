## For running the application execute:

To start the application, use the following Docker command:

```bash
docker-compose up -d --build
```
To create tables, update src/config/bootstrap.php with your database credentials and run the following command:

```bash
php mysql_migration.php
```

Afterwards, open a web browser and navigate to:

```
http://localhost:8080
```
## Web
### Steps
 - Go to /auth/login and register a user with email 'admin@admin.test' so you can login as admin.
 - After successful login as admin go to /web/products to view products and add new products.
 - To add a new product fill form at the bottom of page and click "Create Product" button. You'll be redirected to /web/product page.
 - To edit a product click on edit link of required product. You'll be redirected to /web/product/show?productId=id page.
 - CSRF protection is enabled for all POST requests.
 - AuthMiddleware with admin role restriction is enabled for all actions in admin panel.
### General information
 - Web is handled via `Http\Controller\WebController.php` controller. Views are located in `Views` directory.
 - Auth pages are handled via `Http\Controller\AuthController.php` controller. Views are located in `Views` directory.
 - Path for each page is defined in `index.php` file.
 - To render view, return `Http\Core\Response` object from controller endpoint with view name, data array, and content type text/html.
 - To render template inside of another template, use `renderTemplate` method from `Helpers\web_helper.php`.
 - To ensure CSRF protection, `Http\Middlewares\CSRFMiddleware` is being used for existing requests.
 - To ensure user authentication, `Http\Middlewares\AuthMiddleware` is being used for existing requests with required role of Admin.

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


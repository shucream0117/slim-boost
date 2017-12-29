# SlimBoost

- Slim3
- PHP ≧ 7.1.x

おためし版です

# Routes Example

```php:routes.php
$app->get("/", IndexController::class . ':showIndex');

$app->get("/users/{user_id:[0-9]+}", UserController::class . ':showUserById');
```

# Setting Example

```php:setting.php
'foundHandler' => function () {
    return new FoundHandler();
},
```
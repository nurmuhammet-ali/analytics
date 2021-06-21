# Laravel Analytics

## Installation

Install the package:

```bash
composer require nurmuhammet.ali/analytics
```

Publish the config file and assets:

```bash
php artisan vendor:publish --provider="Nurmuhammet\Analytics\AnalyticsServiceProvider"
```

Don't forget to run the migrations:

```bash
php artisan migrate
```

You can add the page view middleware to a specific route group, e.g. `web.php` like so:

```php
Route::middleware('analytics')->group(function () {
    // ...
});
```

Or add the page view to all middlewares/on an application level like so:

```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \Nurmuhammet\Analytics\Http\Middleware\Analytics::class,
];
```

## Contributing

You're more than welcome to submit a pull request, or if you're not feeling up to it - create an issue so someone else can pick it up.

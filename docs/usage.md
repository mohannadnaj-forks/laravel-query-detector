---
title: Usage
order: 2
---

## Usage

If you run your application in the `debug` mode, the query monitor will be automatically active. So there is nothing you have to do.

By default, this package will log to the `daily` channel, and will display an `alert()` message to notify you about an N+1 query found in the current request.

If you rather want this information to a different log channel, written to your browser's console log as a warning or listed in a new tab for the [Laravel Debugbar (barryvdh/laravel-debugbar)](https://github.com/barryvdh/laravel-debugbar), you can publish the configuration file and change the output behaviour (see examples below).

### Publishing Configuration:

You can publish the package's configuration using this command:

``` bash
php artisan vendor:publish --provider="BeyondCode\QueryDetector\QueryDetectorServiceProvider"
```

This will add the `querydetector.php` file in your config directory.

### Lumen Usage

If you use **Lumen**, you need to copy the [config file](https://raw.githubusercontent.com/beyondcode/laravel-query-detector/master/config/config.php) manually and register the Lumen Service Provider in `bootstrap/app.php` file:

``` php
$app->register(\BeyondCode\QueryDetector\LumenQueryDetectorServiceProvider::class);
```

### Events

If you need additional logic to run when the package detects unoptimized queries, you can listen to the `\BeyondCode\QueryDetector\Events\QueryDetected` event and write a listener to run your own handler. (e.g. send warning to Sentry/Bugsnag, send Slack notification, etc.)

``` php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use BeyondCode\QueryDetector\Events\QueryDetected;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Event::listen(function (QueryDetected $event) {
            $event->getQueries(); // Collection of unoptimized queries
        });
    }
}
```

### Usage in Unit Tests:

Register a listener for the event `BeyondCode\QueryDetector\Events\QueryDetected::class` to -for example- throw an event or log to a file in your `tests/TestCase.php` file:

```
        Event::listen(function (QueryDetected $event) {
            info()
            new \Exception(
                "N+1 Queries Detected! " .
                json_encode($event->getQueries())
            );
        });
```

###  Configuration Options:

Available configuration options in `config/querydetector.php`:

#### Enable or disable the query detection

If this is set to "null", the `app.debug` config value will be used.

``` php
    'enabled' => env('QUERY_DETECTOR_ENABLED', null),
```

#### Threshold level

Threshold level for the N+1 query detection. If a relation query will be executed more then this amount, the detector will notify you about it.

``` php
    'threshold' => (int) env('QUERY_DETECTOR_THRESHOLD', 1),
```

#### Except: Whitelisting Model Relations

If you needed to whitelist a model relations from being reported by the query detector, You have to define the model relation both as the class name and the attribute name on the model. So if an `Author` model would have a `posts` relation that points to a `Post` class, you need to add both the `'posts'` attribute and the `Post::class`, since the relation can get resolved in multiple ways.

``` php
    'except' => [
        //Author::class => [
        //    Post::class,
        //    'posts',
        //]
    ],
```

#### 


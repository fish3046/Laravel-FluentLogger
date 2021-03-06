# laravel-fluent-logger
fluent logger for laravel
(with Monolog handler for Fluentd )

[fluentd](http://www.fluentd.org/)

[![Tests](https://github.com/ytake/Laravel-FluentLogger/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/ytake/Laravel-FluentLogger/actions/workflows/php.yml)
[![Coverage Status](http://img.shields.io/coveralls/ytake/Laravel-FluentLogger/master.svg?style=flat-square)](https://coveralls.io/r/ytake/Laravel-FluentLogger?branch=master)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/ytake/Laravel-FluentLogger.svg?style=flat)](https://scrutinizer-ci.com/g/ytake/Laravel-FluentLogger/?branch=master)

[![License](http://img.shields.io/packagist/l/ytake/laravel-fluent-logger.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-fluent-logger)
[![Latest Version](http://img.shields.io/packagist/v/ytake/laravel-fluent-logger.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-fluent-logger)
[![Total Downloads](http://img.shields.io/packagist/dt/ytake/laravel-fluent-logger.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-fluent-logger)

## usage

### Installation For Laravel
Require this package with Composer

```bash
$ composer require ytake/laravel-fluent-logger
```

or composer.json

```json
"require": {
  "ytake/laravel-fluent-logger": "^5.0"
},
```

**Supported Auto-Discovery(^Laravel5.5)**

## for laravel

your config/app.php

```php
'providers' => [
    \Ytake\LaravelFluent\LogServiceProvider::class,
]
```

### publish configure

* basic

```bash
$ php artisan vendor:publish
```

* use tag option

```bash
$ php artisan vendor:publish --tag=log
```

* use provider

```bash
$ php artisan vendor:publish --provider="Ytake\LaravelFluent\LogServiceProvider"
```

## for Lumen

use `Ytake\LaravelFluent\LumenLogServiceProvider`
  
bootstrap/app.php

```php
$app->register(\Ytake\LaravelFluent\LumenLogServiceProvider::class);
```

Lumen will use your copy of the configuration file if you copy and paste one of the files into a config directory within your project root.

```bash
cp vendor/ytake/laravel-fluent-logger/src/config/fluent.php config/
```

### Config

edit config/fluent.php
```php
return [

    'host' => env('FLUENTD_HOST', '127.0.0.1'),

    'port' => env('FLUENTD_PORT', 24224),

    /** @see https://github.com/fluent/fluent-logger-php/blob/master/src/FluentLogger.php */
    'options' => [],

    /** @see https://github.com/fluent/fluent-logger-php/blob/master/src/PackerInterface.php */
    // specified class name
    'packer' => null,
    
    // optionally override Ytake\LaravelFluent\FluentHandler class to customize behaviour
    'handler' => null,
    
    'processors' => [],

    'tagFormat' => '{{channel}}.{{level_name}}',
];

```

added config/logging.php

```php
return [
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            // always added fluentd log handler
            // 'channels' => ['single', 'fluent'],
            // fluentd only
            'channels' => ['fluent'],
        ],

        'fluent' => [
            'driver' => 'fluent',
            'level' => 'debug',
        ],
        
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
    ],
];

```

or custom / use `via`

```php
return [
    'channels' => [
        'custom' => [
            'driver' => 'custom',
            'via' => \Ytake\LaravelFluent\FluentLogManager::class,
        ],
    ]
];

```

## fluentd config sample

```
## match tag=local.** (for laravel log develop)
<match local.**>
  type stdout
</match>
```

example (production)

 ```
<match production.**>
  type stdout
</match>
 ```
 and more

## for lumen


## fluentd config sample(lumen)

```
<match lumen.**>
  type stdout
</match>
```

## Monolog processors

You can add processors to the monolog handlers by adding them to the `processors` array within the `fluent.php` config.

config/fluent.php:
```php
'processors' => [function($record) {
    $record['extra']['level'] = $record['level_name'];
    
    return $record;
}],
```

Alternatively, you can pass the class name of the processor.  This helps keep your config compatible with `config:cache`

config/fluent.php:
```php
'processors' => [CustomProcessor::class],
```

CustomProcessor.php:
```php
class CustomProcessor
{
    public function __invoke($record)
    {
        $record['extra']['level'] = $record['level_name'];

        return $record;
    }
}
```

## Author ##

- [Yuuki Takezawa](mailto:yuuki.takezawa@comnect.jp.net) ([twitter](http://twitter.com/ex_takezawa))

## License ##

The code for laravel-fluent-logger is distributed under the terms of the MIT license.

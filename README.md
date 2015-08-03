env-checker
===========
Artisan command to check if all required variables are configured in .env

* [Usage](#usage)
* [Envoy usage example](#envoy-usage-example)
* [Requirements](#requirements)
* [License](#license)
* [Author](#author)

Usage:
------
1. Run

```bash
$ composer require baopham/env-checker
```

2. Register in your `config/app.php`:

```php
'providers' => [
    ...
    BaoPham\EnvChecker\EnvCheckerServiceProvider::class,
    ...
];
```

3. Publish the config file:

```bash
$ php artisan vendor:publish --provider="BaoPham\EnvChecker\EnvCheckerServiceProvider"
```

4. Configure the new config file `config/envchecker.php`:

```php
return [
    'variables' => [
        'APP_ENV',
        'APP_KEY',
        'APP_URL',
        'DB_HOST',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
        // any other variables required for your app.
    ],
];
```

5. Run the command

```bash
$ php artisan envchecker:check
```

Envoy Usage Example
-------------------

1. Include this in your `Envoy.blade.php`:

```
@task('check_env')
    git fetch
    git checkout origin/{{ $branch }} -- config/envchecker.php
    php artisan envchecker:check
@endtask

@task('deploy_dev', ['on' => 'dev'])
    envoy run check_env --branch=dev
    if [ $? -eq 0 ]; then
        // continue to pull the latest code and deploy.
    else
        echo 'Env check failed'
        exit 42
    fi
@endtask
```

Requirements:
-------------
Laravel 5.1

License:
--------
MIT

Author:
-------
Bao Pham

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
1) Run

```bash
$ composer require baopham/env-checker
```

2) Register in your `config/app.php`:

```php
'providers' => [
    ...
    BaoPham\EnvChecker\EnvCheckerServiceProvider::class,
    ...
];
```

3) Publish the config file:

```bash
$ php artisan vendor:publish --provider="BaoPham\EnvChecker\EnvCheckerServiceProvider"
```

4) Configure the new config file `config/envchecker.php`:

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

5) Run the command

```bash
$ php artisan envchecker:check
```

Envoy Usage Example
-------------------

Include this in your `Envoy.blade.php`:

```
@task('deploy_dev', ['on' => 'dev'])
    cd /home/forge/app
    git fetch
    git checkout origin/dev -- config/envchecker.php
    php artisan envchecker:check
    // If the check above fails, the script stops here.
    // else, it continues (you can continue to pull the latest code and deploy)
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

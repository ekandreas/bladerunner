# Bladerunner

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://packagist.org/packages/ekandreas/bladerunner)
[![Build Status](https://travis-ci.org/ekandreas/bladerunner.svg?branch=master)](https://travis-ci.org/ekandreas/bladerunner)
[![StyleCI](https://styleci.io/repos/48002506/shield)](https://styleci.io/repos/48002506)
[![Twitter Follow](https://img.shields.io/twitter/follow/shields_io.svg?style=social)](https://twitter.com/intent/user?screen_name=aekabse)


WordPress plugin for Laravel Blade templating.

To install it to your Composer based WordPress installation:

```
composer require ekandreas/bladerunner:*
```
Activate the plugin inside WordPress and templates with *.blade.php are inspected and active.
Your theme still needs an index.php due to WordPress basic functionality. When removed the theme is known as broken.

If you don't use a composer based WordPress development environment you can download the latest distributed plugin at [Bladerunner site http://bladerunner.aekab.se](http://bladerunner.aekab.se) and install it the common way with zip upload to WordPress via wp-admin.

## Hello World
1. Install the library with composer
2. Make sure the cache-folder is writeable in uploads, eg ../wp-content/uploads/.cache
3. Activate the plugin
4. Create a new template file inside your theme, home.blade.php
5. Code example for the file home.blade.php:
```
Hello World Page rendered at {{ date('Y-m-d H:i:s') }}
```
6. I you got the date to render a real date then you can use all the Laravel 5 Blade syntax inside your theme templates!

https://laravel.com/docs/5.2/blade

## Cache
* If WP_DEBUG is set and true then templates always will be rendered and updated.
* View files (cache) is invalidated at save_post.
* (It's a really good idea to empty the .cache folder inside "uploads" when develop templates. Eg, create a "del" command inside your gulp-file.)

## Directories
* Your cached views will always be stored in your wp upload folder, .cache.
* Your views must be placed within your theme folder.
* Your templates must have .blade.php extension.

## Pass data to template
A simple way to pass data to a view before it's loaded.

Run the below code (and change variableName and $value) before the template_include filter (or in the template_include filter with higher priority than 999) to pass data to the soon to be loaded view.
```
\Bladerunner\Template::$data['variableName'] = $value;
```

Inside your view file you will be able to access the passed data like so:
```
{{ $variableName }}
```

## Links
* [Bladerunner site with documentation and distro](http://bladerunner.aekab.se)
* [Docs Laravel Blade v5.2](https://laravel.com/docs/5.2/blade)
* [Packagist](https://packagist.org/packages/ekandreas/bladerunner)
* [Code repo at Github](https://github.com/ekandreas/bladerunner)
* [Support / Issues](https://github.com/ekandreas/bladerunner/issues)

## Tests

### Test requirements:
* Latest Docker install (not the old school Boot2Docker)
* PHP Composer
Currently only tested on OSX.

### Test step by step
Checkout the components for testing via Composer inside the repo:
```bash
composer update
```

Using *Testrunner* (required-dev package) and Docker the test should be exexuted with a single command:
```bash
vendor/bin/dep testrunner
```


# Bladerunner

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/ekandreas/bladerunner)
[![Build Status](https://travis-ci.org/ekandreas/bladerunner.svg?branch=master)](https://travis-ci.org/ekandreas/bladerunner)
[![StyleCI](https://styleci.io/repos/48002506/shield)](https://styleci.io/repos/48002506)
[![GitHub release](https://img.shields.io/github/release/ekandreas/bladerunner.svg)](http://bladerunner.aekab.se/bladerunner.zip)
[![Twitter Follow](https://img.shields.io/twitter/follow/aekabse.svg?style=social)](https://twitter.com/intent/user?screen_name=aekabse)


WordPress plugin for Laravel Blade templating.

To install it to your Composer based WordPress installation:

```sh
$ composer require ekandreas/bladerunner
```

1. Activate the plugin!
2. Add global function `view` or `bladerunner` inside your ordinary WP templates. 

If you don't use a composer based WordPress development environment you can download the latest distributed plugin at [Bladerunner site http://bladerunner.elseif.se](http://bladerunner.elseif.se) and install it the common way with zip upload to WordPress via wp-admin.

## Release 1.6.1 and 1.6.2

Just to update Laravel Blade engine upgrades

## Release 1.6

Laravel 5.4 with Components and Slots!
This is a completely rewrite, perhaps v2? Extracted from Roots Sage.
Some breaking changes:
* Laravel Config and View v5.4, these are in dev mode right now.
* Global function view over old global bladerunner for no echo as default.
* No template filters. You need to use "view" or "bladerunner" global functions in your ordinary WordPress templates.
* No WP admin pages, this is a dev tool :-)

## Release 1.5

Now only supports PHP 5.6 and greater.
Laravel 5.3 is used as blade base.

## Hello World

1. Install the library with Composer.

2. Make sure the cache-folder is writeable in uploads, eg `../wp-content/uploads/.cache`.

3. Activate the plugin.

4. Create a view, eg:

    ```twig
    <!-- view file: views/pages/index.blade.php -->
    
    Hello World Page rendered at {{ date('Y-m-d H:i:s') }}
    ```
    
5. In your index.php, add a global call for the view created, eg:

    ```php
    bladerunner('views.pages.index');
    ```

[https://laravel.com/docs/5.4/blade](https://laravel.com/docs/5.4/blade)

## Cache

* If `WP_DEBUG` is set and true then templates always will be rendered and updated.
* (It's a really good idea to empty the `.cache` folder inside `uploads` when you develop templates. Eg, create a "del" command inside your gulp-file.)

## Directories

* Your cached views will always be stored in your wp upload folder, `.cache`.
* Your views must be placed within your themes folder.
* Your views should have `.blade.php` extension to compile.

## Template Helper

There is a template helper function named `view`, defined globally to use in standard WordPress templates.

Example:
You want to create a 404-template and don't want to use the `.blade.php` extension to the template file.

* Create a `404.php` file in the root of your theme.
* Add the following code to the template:

```php
echo view('views.pages.404');
```

* In the folder `views/pages`, create a blade template `404.blade.php`.

You can pass any data with the global `bladerunner` function like so,

```php
echo view('views.pages.404', ['module'=>$module]);
```

or use compact, eg:

```php
echo view('views.pages.404', compact('module'));
```

## Links

* [Bladerunner Documentation](http://bladerunner.aekab.se)
* [Blade Documentation](https://laravel.com/docs/5.4/blade)
* [Packagist](https://packagist.org/packages/ekandreas/bladerunner)
* [Github](https://github.com/ekandreas/bladerunner)
* [Support / Issues](https://github.com/ekandreas/bladerunner/issues)

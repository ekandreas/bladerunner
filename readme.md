# Bladerunner

[![StyleCI](https://styleci.io/repos/48002506/shield)](https://styleci.io/repos/48002506)

WordPress plugin for Blade L5 templating

To install it to your Composer based WordPress installation:

```
composer require ekandreas/bladerunner:*
```
Activate the plugin inside WordPress and templates with *.blade.php are inspected and active.
Your theme still needs an index.php due to WordPress basic functionality. When removed the theme is known as broken.

If you don't use a composer based WordPress development environment you can download the latest distributed plugin at [AEKAB, Bladerunner site](http://bladerunner.aekab.se/bladerunner.zip) and install it the common way with zip upload to WordPress via wp-admin.

## Hello World
1. Install the library with composer
2. Make sure the cache-folder is writeable in uploads, eg ../wp-content/uploads/.cache
3. Activate the plugin
4. Create a new template file inside your theme, index.blade.php
5. Code example for the file index.blade.php:
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

## Links
* [Docs Laravel Blade v5.2](https://laravel.com/docs/5.2/blade)
* [Packagist](https://packagist.org/packages/ekandreas/bladerunner)
* [Code repo at Github](https://github.com/ekandreas/bladerunner)
* [Support / Issues](https://github.com/ekandreas/bladerunner/issues)
* [Get a WordPress zip distro](http://bladerunner.aekab.se)

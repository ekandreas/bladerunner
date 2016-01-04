# Bladerunner
WordPress plugin for Blade L5 templating

*** WORK IN PROGRESS ***

To install it to your Composer based WordPress installation:

```
composer require ekandreas/bladerunner:*
```

Activate the plugin inside WordPress and templates with *.blade.php are inspected and active.

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

http://laravel.com/docs/master/blade

## Directories
* Your cached views will always be stored in your wp upload folder, .cache.
* Your views must be placed within your theme folder.
* Your templates must have .blade.php extension.


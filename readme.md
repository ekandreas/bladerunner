# Bladerunner

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/ekandreas/bladerunner)
[![Build Status](https://travis-ci.org/ekandreas/bladerunner.svg?branch=master)](https://travis-ci.org/ekandreas/bladerunner)
[![StyleCI](https://styleci.io/repos/48002506/shield)](https://styleci.io/repos/48002506)
[![GitHub release](https://img.shields.io/github/release/ekandreas/bladerunner.svg)](http://bladerunner.aekab.se/bladerunner.zip)
[![Twitter Follow](https://img.shields.io/twitter/follow/elseifab.svg?style=social)](https://twitter.com/intent/user?screen_name=elseifab)


WordPress plugin for Laravel Blade templating.

To install it to your Composer based WordPress installation:

```
composer require ekandreas/bladerunner:*
```
Activate the plugin inside WordPress and templates with *.blade.php are inspected and active.
Your theme still needs an index.php due to WordPress basic functionality. When removed the theme is known as broken.

If you don't use a composer based WordPress development environment you can download the latest distributed plugin at [Bladerunner site http://bladerunner.aekab.se](http://bladerunner.aekab.se) and install it the common way with zip upload to WordPress via wp-admin.

Releases to this plugin is listed last in this readme.

## Hello World
1. Install the library with composer
2. Make sure the cache-folder is writeable in uploads, eg ../wp-content/uploads/.cache
3. Activate the plugin
4. Create a view, eg:
```twig
<!-- view file: views/pages/index.blade.php -->
Hello World Page rendered at {{ date('Y-m-d H:i:s') }}
```
5. In your index.php, add a global call for the view created, eg:
```php
<?php
    bladerunner('views.pages.index')
```

[https://laravel.com/docs/5.2/blade](https://laravel.com/docs/5.2/blade)

## Cache
* If WP_DEBUG is set and true then templates always will be rendered and updated.
* View files (cache) is invalidated at save_post.
* (It's a really good idea to empty the .cache folder inside "uploads" when develop templates. Eg, create a "del" command inside your gulp-file.)

## Directories
* Your cached views will always be stored in your wp upload folder, .cache.
* Your views must be placed within your theme folder.
* Your views must have .blade.php extension.

## Template helper
There is a template helper function named "bladerunner", defined globally to use in standard WordPress templates.

Example:
You want to create a 404-template and don't want to use the .blade.php extension to the template file.

* Create a 404.php in the theme root.
* Add the following code to the template:
```php
<?php
    bladerunner('views.pages.404');
```
* In the folder "views/pages", create a blade template "404.blade.php".

You can pass any data with the global "bladerunner" function like so,
```php
<?php
    bladerunner('views.pages.404', ['module'=>$module]);
```
or use compact, eg:
```php
<?php
    bladerunner('views.pages.404', compact('module'));
```

## Hooks & Filters
Bladerunner continuously implements filters and hooks to modify values and processes.

### Hooks
...

### Filters
Modify Bladerunners cache folder path, default ../wp-content/uploads/.cache
```php
add_filter('bladerunner/cache/path', function() {
	return '/my/path/to/cache';
});
```

Permission settings to cache folder, default 777
```php
add_filter('bladerunner/cache/permission', function() {
	return 644;
});
```
If you don't want Bladerunner to check for permissions form cache folder then set the return to null, eg:
```php
add_filter('bladerunner/cache/permission', '__return_null');
```
If you wan't to customize the base path where you have your views stored, use:
```php
add_filter('bladerunner/template/bladepath', function ($path) { return $path . '/views'; });
```

#### Custom extensions
If you are comfortable with regular expressions and want to add your own extensions to your templates use the filter ``bladerunner/extend``.
Note! It takes one *array* as parameter and requires two keys; "pattern" and "replace".

```php
$extensions[] = [
	'pattern' => '...',
	'replace' => '...',
];
```

Use the filter as possible way to add your own custom extensions.

In this example we want to add ``@mysyntax`` as a custom extension.
```php
/*
 * Add custom extension @mysyntax to Bladerunner
 */
add_filter('bladerunner/extend', function($extensions) {
    $extensions[] = [
    	'pattern' => '/(\s*)@mysyntax(\s*)/',
    	'replace' => '$1<?php echo "MYSYNTAX COMPILED!"; ?>$2',
    ];
    return $extensions;
});
```
Then use your new syntax inside a WordPress blade template like so:
```php
	@mysyntax
```

We will soon add more WordPress extenstions to the Bladerunner engine. Please give us your great examples to implement!

#### Template Data Filter
A simple way to pass data to a given view before it's loaded.

Set the filter ``bladerunner/templates/data/{view}`` before running a template to pass custom data to the template, eg:
```php
$data = [
	'this' => 'that',
	'other' => 'perhaps',
];
add_filter('bladerunner/templates/data/single', $data);
```

Inside your "single.blade.php" / view file you will be able to access the passed data like so:
```php
{{ $data['this'] }}
{{ $data['other'] }}
```

Default value for data is an empty array.

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

## Releases

### 2.0-beta.1
The working release with the controller concept included.

### Release 1.6.1 and 1.6.2
Just to update Laravel Blade engine upgrades

### Release 1.6
Laravel 5.4 with Components and Slots!
This is a completely rewrite, perhaps v2? Extracted from Roots Sage.
Some breaking changes:
* Laravel Config and View v5.4, these are in dev mode right now.
* Global function view over old global bladerunner for no echo as default.
* No template filters. You need to use "view" or "bladerunner" global functions in your ordinary WordPress templates.
* No WP admin pages, this is a dev tool :-)

### Release 1.5
Now only supports PHP5.6 and greater.
Laravel 5.3 is used as blade base.



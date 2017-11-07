# Bladerunner

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/ekandreas/bladerunner)
[![Build Status](https://travis-ci.org/ekandreas/bladerunner.svg?branch=master)](https://travis-ci.org/ekandreas/bladerunner)
[![StyleCI](https://styleci.io/repos/48002506/shield)](https://styleci.io/repos/48002506)
[![GitHub release](https://img.shields.io/github/release/ekandreas/bladerunner.svg)](http://bladerunner.aekab.se/bladerunner.zip)
[![Twitter Follow](https://img.shields.io/twitter/follow/elseifab.svg?style=social)](https://twitter.com/intent/user?screen_name=elseifab)


WordPress plugin for Laravel Blade templating.

To install it to your Composer based WordPress installation:

```
composer require ekandreas/bladerunner
```
Activate the plugin inside WordPress and templates with *.blade.php are inspected and active.
Your theme still needs an index.php due to WordPress basic functionality. When removed the theme is known as broken.

If you don't use a composer based WordPress development environment you can download the latest distributed plugin at [Bladerunner site http://bladerunner.aekab.se](http://bladerunner.aekab.se) and install it the common way with zip upload to WordPress via wp-admin.

Releases to this plugin is listed last in this readme.

## Hello World
1. Install the library with composer
2. Make sure the cache-folder is writeable in uploads, eg `../wp-content/uploads/.cache`
3. Activate the plugin
4. Create a view, eg:
```twig
<!-- view file: views/pages/index.blade.php -->
Hello World Page rendered at {{ date('Y-m-d H:i:s') }}
```
5. In your `index.php`, add a global call for the view created, eg:
```php
<?php
    bladerunner('views.pages.index')
```

[https://laravel.com/docs/5.2/blade](https://laravel.com/docs/5.2/blade)

## Cache
* If `WP_DEBUG` is set and true then templates always will be rendered and updated.
* View files (cache) is invalidated at `save_post`
* (It's a really good idea to empty the .cache folder inside `uploads` when develop templates. Eg, create a `del` command inside your gulp-file.)

## Directories
* Your cached views will always be stored in your wp upload folder, `.cache`
* Your views must be placed within your theme folder.
* Your views must have `.blade.php` extension.

## Template helper
There is a template helper function named `bladerunner`, defined globally to use in standard WordPress templates.

Example:
You want to create a 404-template and don't want to use the `.blade.php` extension to the template file.

* Create a 404.php in the theme root.
* Add the following code to the template:
```php
<?php
    bladerunner('views.pages.404');
```
* In the folder `views/pages`, create a blade template `404.blade.php`

You can pass any data with the global `bladerunner` function like so,
```php
<?php
    bladerunner('views.pages.404', ['module'=>$module]);
```
or use compact, eg:
```php
<?php
    bladerunner('views.pages.404', compact('module'));
```

## Controllers
With version 1.7 controllers are added to Bladerunner.
As default Bladerunner will look for extended classes in the theme folder + /controllers.
If you would like to add or change the controller paths take a look below at filters!

The controller class has to extend \Bladerunner\Controller to work.
It will guess the path to the view but you can override this with `protected $view='your.custom.view.path''`

The controller files follow the same hierarchy as WordPress.
You can view the controller hierarchy by using the Blade directive `@debug`.

Extend the Controller Class, it is recommended that the class name matches the filename.
Create methods within the Controller Class:
* Use public function to expose the returned values to the Blade views/s.
* Use public static function to use the function within your Blade view/s.
* Use protected function for internal controller methods as only public methods are exposed to the view. You can run them within `__construct`

### Controller example: 

The following example will expose `$images` to `views/single.blade.php` 

**controllers/Single.php**

```php
<?php

namespace App;

use Bladerunner\Controller;

class Single extends Controller
{
    /**
     * Return images from Advanced Custom Fields
     *
     * @return array
     */
    public function images()
    {
        return get_field('images');
    }
}
```

**views/single.blade.php**

```php
@if($images)
  <ul>
    @foreach($images as $image)
      <li><img src="{{$image['sizes']['thumbnail']}}" alt="{{$image['alt']}}"></li>
    @endforeach
  </ul>
@endif
```

## Hooks and Filters
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

If you don't want Bladerunner to create the cache folder:
```php
add_filter('bladerunner/cache/make', function() {
    return false;
});
```

If you wan't to customize the base paths where you have your views stored, use:
```php
add_filter('bladerunner/template/bladepath', function ($paths) {
    if (!is_array($paths)) {
        $paths = [$paths];
    }
    $paths[] = ABSPATH . '../../resources/views';
    return $paths;
});
```
If you wan't to customize the controller paths where you have your controllers stored, use:
```php
add_filter('bladerunner/controller/paths', function ($paths) { 
    $paths[] = PLUGIN_DIR . '/my-fancy-plugin/controllers';
    return $path; 
});
```

We will soon add more WordPress extenstions to the Bladerunner engine. Please give us your great examples to implement!

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

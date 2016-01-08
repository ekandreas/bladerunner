<?php

// Load Composer autoload.
require __DIR__ . '/../vendor/autoload.php';

// Define fixtures directory constant
define('BLADERUNNER_FIXTURE_DIR', __DIR__ . '/data');

// Load Papi loader file as plugin.
WP_Test_Suite::load_plugins(__DIR__ . '/../bladerunner.php');

// Load our helpers file.
WP_Test_Suite::load_files([
]);

// Run the WordPress test suite.
WP_Test_Suite::run();

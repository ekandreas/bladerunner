<?php
date_default_timezone_set('Europe/Stockholm');

include_once 'vendor/deployer/deployer/recipe/common.php';
include_once 'vendor/ekandreas/testrunner/recipe.php';

set('docker_host_name','test');

<?php
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);

if (file_exists(ROOT . 'vendor/autoload.php')) {
    require ROOT . 'vendor/autoload.php';
}
//load configuration     
require APP . '/config/config.php';

//load libs
//require APP . '/libs/helper.php';

//load application
require APP . '/core/application.php';
require APP . '/core/controller.php';

$app = new Application();
<?php
declare(strict_types=1);

require_once '../src/App.php';
require_once 'autoload.php';
require_once './../vendor/autoload.php';
require_once 'Util/Util.php';

use App\App;

session_start();

new App();


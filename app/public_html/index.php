<?php

require_once '../autoload.php';

use App\Application;

$config = require '../config.php';

(new Application($config))->run();

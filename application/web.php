<?php

namespace application;

use vendor\Log\Logger;
use vendor\Log\LogLevel;

$comfig = require_once '/config/app.php';

//$log = new Logger();
//
//$log->log(LogLevel::ALERT, 'alert {user}', ['user' => 'roma']);

$first = new first(new Logger(true, true));

$first->doSomething();

$first->error();
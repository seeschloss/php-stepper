<?php

require __DIR__.'/stepper.inc.php';
require_once __DIR__.'/settings.inc.php';

Settings::$VERBOSE = in_array('--verbose', $argv);
Settings::$DRY_RUN = in_array('--dry-run', $argv);

$stepper = new Stepper(array(
	new Coil(new GPIO( 6), new GPIO(12)),
	new Coil(new GPIO(20), new GPIO(21)),
), 513);

$stepper->steps(20);
$stepper->neutral();


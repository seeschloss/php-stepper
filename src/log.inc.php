<?php

require_once __DIR__.'/settings.inc.php';

class Log {
	static function verbose($message) {
		if (Settings::$VERBOSE) {
			echo $message;
		}
	}
}


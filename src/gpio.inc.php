<?php

require_once __DIR__.'/settings.inc.php';

class GPIO {
	public static $base_path = '/sys/class/gpio';

	public $number;
	public $base_dir;

	static function _put($file, $value) {
		if (!Settings::$DRY_RUN) {
			file_put_contents($file, $value);
		}
	}

	static function setup($number) {
		$dir = self::$base_path . '/gpio' . (int)$number;

		if (!file_exists($dir)) {
			$export = self::$base_path . '/export';

			self::_put($export, (int)$number);
		}

		return file_exists($dir);
	}

	function __construct($number, $direction = "out") {
		$this->number = (int)$number;
		$this->base_dir = GPIO::$base_path . '/gpio' . $this->number;

		$this->set_direction($direction);

		GPIO::setup($number);
	}

	function set_direction($direction) {
		if ($direction === 'in' or $direction === 'out' and $this->get_direction() != $direction) {
			$direction_file = $this->base_dir . '/direction';
			GPIO::_put($direction_file, $direction);
		}

		return $this->get_direction();
	}

	function get_direction() {
		$direction_file = $this->base_dir . '/direction';
		return trim(file_get_contents($direction_file));
	}

	function set($value) {
		$value_file = $this->base_dir . '/value';
		GPIO::_put($value_file, $value);
	}

	function get() {
		$value_file = $this->base_dir . '/value';
		return trim(file_get_contents($value_file));
	}
}


<?php

require_once __DIR__.'/gpio.inc.php';
require_once __DIR__.'/settings.inc.php';
require_once __DIR__.'/log.inc.php';

class Stepper {
	public $coils;
	public $steps;

	function __construct($coils, $steps = 0) {
		$this->coils = $coils;
		$this->steps = $steps;
	}

	function steps($n, $speed = 0.1) {
		$spinner = array(
			'-',
			'\\',
			'|',
			'/',
		);
		for ($i = 0; $i < $n; $i++) {
			$coil_n = $i % count($this->coils);

			$coil = $this->coils[$coil_n];
			$coil->positive();
			usleep($speed * 1000 * 1000);
			$coil->off();

			Log::verbose("\r".$spinner[$i % count($spinner)]);
		}

		Log::verbose("\n");
	}

	function neutral() {
		foreach ($this->coils as $coil) {
			$coil->off();
		}
	}
}

class Coil {
	public $gpio_1;
	public $gpio_2;

	function __construct($gpio_1, $gpio_2) {
		if (is_int($gpio_1)) {
			$this->gpio_1 = new GPIO($gpio_1);
		} else {
			$this->gpio_1 = $gpio_1;
		}

		if (is_int($gpio_2)) {
			$this->gpio_2 = new GPIO($gpio_2);
		} else {
			$this->gpio_2 = $gpio_2;
		}
	}

	function off() {
		$this->gpio_1->set(0);
		$this->gpio_2->set(0);
	}

	function positive() {
		$this->gpio_1->set(1);
		$this->gpio_2->set(0);
	}

	function negative() {
		$this->gpio_1->set(0);
		$this->gpio_2->set(1);
	}
}


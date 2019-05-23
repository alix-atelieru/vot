<?php
namespace App\Functions;

class Datetime {
	public static function getTimezoneType() {
		return env('TZ');
	}

	public static function getTimezone() {
		return new \DateTimeZone(self::getTimezoneType());
	}

	public static function now() {
		return new \DateTime('now', self::getTimezone());
	}
}
?>
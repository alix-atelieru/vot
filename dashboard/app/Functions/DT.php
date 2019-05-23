<?php
namespace App\Functions;

class DT {
	public static function getTimezoneType() {
		return env('TZ');
	}

	public static function getTimezone() {
		return new \DateTimeZone(self::getTimezoneType());
	}

	public static function now() {
		return new \DateTime('now', self::getTimezone());
	}

	public static function getHourInterval($datetime) {
        $currentHourStart = clone $datetime;
        $currentHourEnd = clone $datetime;
        $currentHourStart->setTime($datetime->format('H'), 0, 0);
        $currentHourEnd->modify("+1 hour");
        $currentHourEnd->setTime($currentHourEnd->format('H'), 0, 0);

        return ['start' => $currentHourStart, 'end' => $currentHourEnd];
	}

	public static function getLastHourInterval($datetime) {
        $oneHourAgo = clone $datetime;
        $oneHourAgo->modify("-1 hour");
        return ['start' => $oneHourAgo, 'end' => $datetime];
	}

	public static function getDayInterval($datetime) {
        $currentDayStart = clone $datetime;
        $currendDayEnd = clone $datetime;
        $currentDayStart->setTime(0,0,0);
        $currendDayEnd->setTime(23,59,59);

        return ['start' => $currentDayStart, 'end' => $currendDayEnd];
	}

	public static function getLastDayInterval($datetime) {
        $oneDayAgo = clone $datetime;
        $oneDayAgo->modify("-24 hours");
        return ['start' => $oneDayAgo, 'end' => $datetime];	
	}

	public static function monthInterval($dayDate) {
		$firstOfMonth = clone $dayDate;
		$firstOfMonth->setDate($firstOfMonth->format('Y'), $firstOfMonth->format('m'), 1);
		$firstOfMonth->setTime(0,0,0);
		$lastOfMonth = clone $dayDate;
		$lastOfMonth->setDate($lastOfMonth->format('Y'), $lastOfMonth->format('m'), $lastOfMonth->format('t'));
		$lastOfMonth->setTime(23,59,59);
		return ['start' => $firstOfMonth, 'end' => $lastOfMonth];
	}

	public static function prevMonthInterval($datetime) {
		$oneMonthAgo = clone $datetime;
		$oneMonthAgo->modify('-1 month');
		return self::monthInterval($oneMonthAgo);
	}

	public static function nextMonthInterval($datetime) {
		$nextMonth = clone $datetime;
		$nextMonthNumber = 1;
		$nextMonthYear = $datetime->format('Y');
		$monthNumber = intval($datetime->format('n'));
		if ($monthNumber < 12) {
			$nextMonthNumber = $monthNumber+1;
		} else {
			$nextMonthNumber = 1;
			$nextMonthYear++;
		}
		$nextMonth->setDate($nextMonthYear, $nextMonthNumber, 2);//nu conteaza ziua;
		return self::monthInterval($nextMonth);
	}
}
?>
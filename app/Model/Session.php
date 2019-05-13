<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {
	public $timestamps = false;

	public function user() {
		return $this->belongsTo('App\Model\Observer');
	}

	public static function generateToken($length = 20) {
		$alphabet = range('a', 'z');
		$token = '';
		for($i = 0;$i < $length;$i++) {
			$token .= $alphabet[rand(0,count($alphabet)-1)];
		}

		return $token;
	}

	public static function build() {
		$me = new self();
		$me->token = self::generateToken();
		$me->created_at = new \DateTime('now', \App\Functions\Datetime::getTimezone());
		return $me;
	}
}
?>
<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Functions\DT;

class SessionToken extends Model {
	public $timestamps = false;

	public function observer() {
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
		$me->created_at = new \DateTime('now', \App\Functions\DT::getTimezone());
		return $me;
	}

	/*
	public function initExpirationDate() {
		if (empty($this->created_at)) {
			return 0;
		}

		$this->expires_at = clone $this->created_at;
		$this->expires_at->modify("+30 years");
	}

	public function isExpired($now) {
		return $now > new \DateTime($this->expires_at, DT::getTimezone());
	}

	public function ownedByUser($userId) {
		$token = self::where('token', $this->token)->where('user_id', $userId)->get();
		return !$token->isEmpty();
	}

	public function isValid($now, $userId) {
		return !$this->isExpired($now) && $this->ownedByUser($userId);
	}
	*/
}
?>
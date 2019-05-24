<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class SmsRequest extends Model {
	public $table = 'sms_requests';
	public $timestamps = false;

	public static function getNotCheckedRequests() {
		return self::where('sending_checked', 0)->get();
	}
}

?>
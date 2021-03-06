<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {
	public $timestamps = false;

	const TYPE_JUDET = 'judet';
	const TYPE_NATIONAL = 'national';
	const TYPE_SUPERADMIN = 'superadmin';

	public function judet() {
		return $this->belongsTo('App\Model\Judet');
	}

	public static function hashPassword($cleartextPassword) {
		return md5(env('PASSWORD_SALT:') . $cleartextPassword);
	}

	public static function findByCredentials($username, $password) {

	}

	public static function loginWithDict($dict) {

	}
}

?>
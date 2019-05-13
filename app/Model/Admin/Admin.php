<?php
namespace App\Model\Admin;

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
		return self::where('username', $username)->where('password', self::hashPassword($password))->first();
	}

	//tre sa bagam date si in sesiune;maybe inject the session here?
	public static function loginWithDictAction($dict) {
		if (empty($dict['username']) || empty($dict['password'])) {
			return ['ok' => false, 'error' => 'MISSING_PARAMS', 'errorLabel' => 'Campuri lipsa'];
		}

		$admin = self::findByCredentials($dict['username'], $dict['password']);
		if (empty($admin)) {
			return ['ok' => false, 'error' => 'USER_DOESNT_EXIST', 'errorLabel' => 'Userul nu exista'];	
		}

		return ['ok' => true, 'user' => $admin];
	}
}

?>
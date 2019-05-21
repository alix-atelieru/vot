<?php
namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Model\Judet;

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

	public static function addJudet($dict, $now) {
		if (empty($dict['username']) || empty($dict['password']) || empty($dict['judet_id']) || empty($dict['full_name'])) {
			return ['ok' => false, 'errorLabel' => 'Campuri lipsa'];
		}

		$judet = Judet::find($dict['judet_id']);
		if (empty($judet)) {
			return ['ok' => false, 'errorLabel' => 'Judet inexistent'];
		}

		//username exista?
		$adminUsernameDup = self::where('username', $dict['username'])->first();
		if (!empty($adminUsernameDup)) {
			return ['ok' => false, 'errorLabel' => 'Username exista'];
		}

		$admin = new self();
		$admin->type = self::TYPE_JUDET;
		$admin->judet_id = intval($dict['judet_id']);
		$admin->username = $dict['username'];
		$admin->password = self::hashPassword($dict['password']);
		$admin->full_name = $dict['full_name'];
		$admin->created_at = $now;

		$admin->save();
		return ['ok' => true, 'id' => $admin->id, 'username' => $dict['username'], 'password' => $dict['password']];
	}

	public static function addNational($dict, $now) {
		if (empty($dict['username']) || empty($dict['password']) || empty($dict['full_name'])) {
			return ['ok' => false, 'errorLabel' => 'Campuri lipsa'];
		}

		//username exista?
		$adminUsernameDup = self::where('username', $dict['username'])->first();
		if (!empty($adminUsernameDup)) {
			return ['ok' => false, 'errorLabel' => 'Username exista'];
		}

		$admin = new self();
		$admin->type = self::TYPE_NATIONAL;
		$admin->username = $dict['username'];
		$admin->password = self::hashPassword($dict['password']);
		$admin->full_name = $dict['full_name'];
		$admin->created_at = $now;

		$admin->save();
		return ['ok' => true, 'id' => $admin->id, 'username' => $dict['username'], 'password' => $dict['password']];
	}


	public static function getAdminsFromCsv($csvPath) {
		$f = fopen($csvPath, 'r');
		if ($f === false) {
			throw new Exception('cant fopen');
		}

		$rows = [];
		$columns = ['username', 'password', 'full_name', 'judet', 'type'];
		fgetcsv($f, 10000, ",");//sari capul de tabel
		while (($row = fgetcsv($f, 100000, ",")) !== false) {
			$rowDict = [];
			for($i = 0;$i < count($columns);$i++) {
				$rowDict[$columns[$i]] = $row[$i];
			}

			if ($rowDict['type'] == Admin::TYPE_JUDET) {
				$judet = Judet::where('name', $rowDict['judet'])->first();
				if (!empty($judet)) {
					$rowDict['judet_id'] = $judet->id;	
				}
				
			}
			$rows[] = $rowDict;
		}
		return $rows;
	}

	public static function importAdmins($csvPath, $now) {
		$admins = self::getAdminsFromCsv($csvPath, $now);
		$responses = [];
		foreach ($admins as $admin) {
			if ($admin['type'] == Admin::TYPE_JUDET) {
				$responses[] = self::addJudet($admin, $now);
			} elseif ($admin['type'] == Admin::TYPE_NATIONAL) {
				$responses[] = self::addNational($admin, $now);
			}
		}
		echo '<pre>';
		print_r($responses);
		echo '</pre';
	}
}

?>
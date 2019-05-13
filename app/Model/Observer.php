<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\SessionToken;

class Observer extends Model {
	public $timestamps = false;
	
	public function judet() {
		return $this->belongsTo('App\Model\Judet');
	}

	public function section() {
		return $this->belongsTo('App\Model\Section');
	}

	public static function findByCredentials($phone, $pin) {
		return self::where('phone', $phone)->where('pin', $pin)->first();
	}

	//todo:intoarce si sectiile din judet
	public static function loginAction($dict) {
		if (empty($dict['phone']) || empty($dict['pin'])) {
			return ['ok' => false, 'error' => 'MISSING_PARAMS', 'error_label' => 'Campuri lipsa'];
		}

		$observer = self::findByCredentials($dict['phone'], $dict['pin']);
		if (empty($observer)) {
			return ['ok' => false, 'error' => 'BAD_LOGIN', 'error_label' => 'Date login gresite'];
		}
		//all good
		$token = SessionToken::build();
		$token->observer()->associate($observer);
		$token->save();
		return ['ok' => true, 'token' => $token->token, 'id' => $observer->id];
	}

	/*
	next up->adaugare numaratoare sectie?aici nu am stabilit daca poate sa updateze and whatnot.
	sa facem logini pentru admini?
	atentie la includere css-uri;
	ce rute avem?
	admin/judetean/xxx;Admin/JudeteanController;
	admin/national;Admin/NationalController;
	/admin/admin;Admin/SuperAdminController;
	toti mostenesc din AdminController;
	*/
}

?>
<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\SessionToken;
use App\Model\Judet;
use App\Model\Section;

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
		//judetele alfabetic, sectiile nu conteaza, se rezolva din front;
		return ['ok' => true, 
				'token' => $token->token, 
				'id' => $observer->id,
				'judete' => Judet::orderBy('name', 'ASC')->get(),
				'sectii' => Section::all(['id', 'judet_id', 'nr','adress', 'name'])
				];
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

	/*
	lista care apare la administrare observatori;
	listForSmsQuery
	*/
	public static function listForAdminQueryBody() {
		return "
			from observers
			left join sections on observers.section_id=sections.id
			left join judete on observers.judet_id=judete.id
		";
	}

	public static function listForAdminQueryWhere($filter) {
		$judeteFilter = '(1=1)';
		if (array_key_exists('judet_id', $filter)) {
			if ($filter['judet_id'] === null) {
				$judeteFilter = "(observers.judet_id is null)";
			} else {
				$judetId = intval($filter['judet_id']);
				$judeteFilter = "(observers.judet_id=$judetId)";

			}
		}

		$whereFilter = " where " . $judeteFilter;
		return $whereFilter;
	}

	public static function listForAdminQueryLimit($currentPage, $itemsPerPage) {
		$offset = Pagination::offset($currentPage, $itemsPerPage);
		return " limit $offset, $itemsPerPage";
	}

	public static function listForAdminSelectQuery($filter, $currentPage, $itemsPerPage) {
		$q = "select observers.*,judete.name as judet_name, sections.nr as section_nr ";
		$q .= Observer::listForAdminQueryBody() . ' ';
		$q .= Observer::listForAdminQueryWhere($filter) . ' ';
		$q .= Observer::listForAdminQueryLimit($currentPage, $itemsPerPage);
		return $q;
	}

	public static function listForAdminCountQuery($filter) {
		$q = "select count(observers.id) as observers_count ";
		$q .= Observer::listForAdminQueryBody() . ' ';
		$q .= Observer::listForAdminQueryWhere($filter) . ' ';
		return $q;
	}

	public static function listForAdminSelect($filter, $currentPage, $itemsPerPage) {
		return DB::select(self::listForAdminSelectQuery($filter, $currentPage, $itemsPerPage));
	}

	public static function listForAdminCount($filter) {
		$row = DB::selectOne(self::listForAdminCountQuery($filter));
		if (!empty($row->observers_count)) {
			return $row->observers_count;
		}

		return 0;
	}

	public function phoneExists($phone) {
		$query = DB::raw("select * from observers where id != :observerId and phone = :phone");
		$rows = DB::select($query, ['observerId' => $this->id, 'phone' => $phone]);
		return !empty($rows);
	}

	//maybe wrap this in a transaction with a locked table?
	public function updateByJudetAction($requestDict) {
		$requiredFields = ['family_name', 'given_name', 'phone', 'pin'];
		foreach ($requiredFields as $field) {
			if (empty($requestDict[$field])) {
				return ['ok' => false, 'error' => 'Camp lipsa'];
			}
		}
		
		if (!preg_match('/^[0-9]+$/', $requestDict['phone'])) {
			return ['ok' => false, 'error' => 'Telefon invalid'];
		}

		if (!preg_match('/^[0-9]+$/', $requestDict['pin'])) {
			return ['ok' => false, 'error' => 'Pin invalid'];
		}

		DB::beginTransaction();
		try {
			DB::unprepared('lock tables observers WRITE');
			if ($this->phoneExists($requestDict['phone'])) {
				return ['ok' => false, 'error' => 'Telefonul exista'];
			}

			$this->family_name = $requestDict['family_name'];
			$this->given_name = $requestDict['given_name'];
			$this->phone = $requestDict['phone'];
			$this->pin = $requestDict['pin'];
			$this->save();
			DB::commit();
			DB::unprepared('unlock tables');
			return ['ok' => true];
		} catch(\Exception $e) {
			DB::rollBack();
			return ['ok' => false, 'error' => 'Tranzactie esuata.Incearca din nou.'];
		}
	}
}

?>
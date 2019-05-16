<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Observer;
use App\Model\Pagination;
use App\Functions\DT;
use Illuminate\Support\Facades\DB;

class Section extends Model {
	public $timestamps = false;
	
	public function observers() {
		return $this->hasMany('App\Model\Observer');
	}

	public function judet() {
		return $this->belongsTo('App\Model\Judet');
	}

	public $fillable = ['psd_votes', 'usr_votes', 'alde_votes', 'proromania_votes', 'pmp_votes', 'udmr_votes', 'other_votes'];

	/*
	todo: ia toate campurile la puricat
	se poate ca un vot sa fie 0;deci se poate sa fie empty;array_kexists;
	tre sa verificam cine adauga si daca poate sa adauge
	tre sa vedem sa fie numere intregi pozitive;
	nu doar observatorul poate sa adauge voturi, aparent, ci si admini;
	avem last_count_user_type/last_count_user_id
	todo: vezi sa nu se duplice iar?oricum se face update mereu;
	*/
	public static function addVotesCountAction($requestDict, $sectionId, $userId, $userType) {
		//userul mai poate sa modifice numaratoarea sectiei?
		$section = Section::find($sectionId);
		if (empty($section)) {
			return ['ok' => false, 'errorLabel' => 'Sectia nu exista'];
		}
		//echo $section->last_count_user_type;die;
		/*daca avem last_count_user_id si tipul e peste tipul userului logat, da eroare ca nu poate modifica numaratoarea sectiei;*/
		if (!empty($section->last_count_user_type)) {
			$typesAbove = [];
			if ($userType == Observer::TYPE_OBSERVER) {
				$typesAbove = [Admin::TYPE_JUDET, Admin::TYPE_NATIONAL, Admin::TYPE_SUPERADMIN];
			}
			
			//daca e modificat de acelasi tip de user->permite modificari
			if ($userType == Admin::TYPE_JUDET) {
				$typesAbove = [Admin::TYPE_NATIONAL, Admin::TYPE_SUPERADMIN];
			}

			if ($userType == Admin::TYPE_NATIONAL) {
				$typesAbove = [Admin::TYPE_SUPERADMIN];
			}

			if (in_array($section->last_count_user_type, $typesAbove)) {
				return ['ok' => false, 'errorLabel' => 'Eroare: numaratoare sectie modifcata de un superior'];
			}
		}

		$counterFields = ['psd_votes', 
						  'usr_votes', 
						  'alde_votes', 
						  'proromania_votes', 
						  'pmp_votes', 
						  'udmr_votes', 
						  'prodemo_votes',
						  'psr_votes',
						  'psdi_votes',
						  'pru_votes',
						  'unpr_votes',
						  'bun_votes',
						  'tudoran_votes',
						  'simion_votes',
						  'costea_votes',
						  'other_votes'];
		//verifica sa existe toate campurile pe request:
		foreach ($counterFields as $field) {
			if (!array_key_exists($field, $requestDict)) {
				//echo $field;
				return ['ok' => false, 'errorLabel' => 'Camp lipsa'];
			}
		}

		//todo: de lamurit totalul se trimite sau il calculez eu din acele campuri?
		foreach ($counterFields as $field) {
			if (!preg_match('/^[0-9]+$/', $requestDict[$field])) {
				return ['ok' => false, 'errorLabel' => 'Camp invalid'];
			}

			if (intval($requestDict[$field]) < 0) {
				return ['ok' => false, 'errorLabel' => 'Valoare negativa nepermisa'];
			}
		}

		$totalVotes = 0;
		foreach ($counterFields as $field) {
			$section->{$field} = intval($requestDict[$field]);
			$totalVotes += $section->{$field};
		}

		$section->total_votes = $totalVotes;
		$section->last_count_user_type = $userType;
		$section->last_count_user_id = $userId;
		/*
		daca e observator si apoi vine un superior, o sa se piarda;daca sunt mai multi observatori?
		se pierde observatorul;daca sunt mai multi observatori per sectie?
		daca e observator tre sa salvam sectia la care a pontat in observers;
		counted_section_id_at;tre sa fie ca section_id;
		*/

		if ($userType == Observer::TYPE_OBSERVER) {
			$observer = Observer::find($userId);
			$observer->counted_section_id_at = DT::now();
			$observer->save();
		}

		$section->count_last_updated_at = DT::now();
		$section->save();
		return ['ok' => true];
	}

	/*
		$offset = Pagination::offset($currentPage, $itemsPerPage);
		return " limit $offset, $itemsPerPage";
	*/
	public static function paginatedAll($page, $itemsPerPage, $filter) {
		$offset = Pagination::offset($page, $itemsPerPage);
		$where = '(1=1)';
		if (!empty($filter['judet_id'])) {
			$where = ' sections.judet_id=' . intval($filter['judet_id']);
		}
		$query = "
		select sections.*, judete.name as judet_name 
		from sections
		join judete on judete.id=sections.judet_id
		where $where
		order by judete.name, sections.nr
		limit $offset, $itemsPerPage
		";

		//echo $query;

		return DB::select($query);
	}

	public static function paginatedAllCount($filter) {
		$where = '(1=1)';
		if (!empty($filter['judet_id'])) {
			$where = ' sections.judet_id=' . intval($filter['judet_id']);
		}
		$query = "
		select count(sections.id) as sections_count
		from sections
		join judete on judete.id=sections.judet_id
		where $where
		";
		$row = DB::selectOne($query);
		return $row->sections_count;
	}

}

?>
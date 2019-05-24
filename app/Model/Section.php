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

	public $fillable = ['psd_votes', 
						'pnl_votes',
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
						'a',
						'a1',
						'a2',
						'b',
						'b1',
						'b2',
						'b3',
						'c',
						'd',
						'e',
						'f'
					];

	public static function getCounterFields() {
		return [['field' => 'psd_votes', 'label' => 'PSD'], 
				['field' => 'usr_votes', 'label' => 'USR'],
				['field' => 'proromania_votes', 'label' => 'Proromania'],
				['field' => 'udmr_votes', 'label' => 'UDMR'],
				['field' => 'pnl_votes', 'label' => 'PNL'],
				['field' => 'alde_votes', 'label' => 'ALDE'],
				['field' => 'prodemo_votes', 'label' => 'PRODEMO'],
				['field' => 'pmp_votes', 'label' => 'PMP'],
				['field' => 'psr_votes', 'label' => 'PSR'],
				['field' => 'psdi_votes', 'label' => 'PSDI'],
				['field' => 'pru_votes', 'label' => 'PRU'],
				['field' => 'unpr_votes', 'label' => 'UNPR'],
				['field' => 'bun_votes', 'label' => 'BUN'],
				['field' => 'tudoran_votes', 'label' => 'Gregoriana Tudoran'],
				['field' => 'simion_votes', 'label' => 'George Simion'],
				['field' => 'costea_votes', 'label' => 'Peter Costea']
				];
	}

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
		
		$counterFields = array_column(self::getCounterFields(), 'field');
		$statsFields = ['a',
					   'a1',
					   'a2',
					   'b',
					   'b1',
					   'b2',
					   'b3',
					   'c',
					   'd',
					   'e',
					   'f'];

		$totalVotes = 0;
		foreach ($counterFields as $field) {
			if (!empty($requestDict[$field])) {
				$section->{$field} = intval($requestDict[$field]);
				$totalVotes += $section->{$field};
			} else {
				$section->{$field} = 0;
			}
		}

		foreach ($statsFields as $field) {
			if (array_key_exists($field, $requestDict) && !empty($requestDict[$field])) {
				if (!preg_match('/^[0-9]+$/', $requestDict[$field])) {
					return ['ok' => false, 'errorLabel' => 'Camp invalid'];
				}

				if (intval($requestDict[$field]) < 0) {
					return ['ok' => false, 'errorLabel' => 'Valoare negativa nepermisa'];
				}
			}
		}

		foreach ($statsFields as $field) {
			if (array_key_exists($field, $requestDict)) {
				$section->{$field} = intval($requestDict[$field]);
			}
		}

		$section->total_votes = $totalVotes;
		$section->last_count_user_type = $userType;
		$section->last_count_user_id = $userId;

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
		select sections.*, judete.name as judet_name, judete.id as judet_id
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

	/*
	ia total+per partide
	*/
	public static function countNationalElection() {
		$totalRow = DB::selectOne("select sum(total_votes) as total_votes from sections");
		$totalVotes = $totalRow->total_votes;
		
		$partyTotals = DB::selectOne(
			"
			select sum(psd_votes) as psd_votes,
			sum(pnl_votes) as pnl_votes,
			sum(usr_votes) as usr_votes,
			sum(alde_votes) as alde_votes,
			sum(proromania_votes) as proromania_votes,
			sum(pmp_votes) as pmp_votes,
			sum(udmr_votes) as udmr_votes,
			sum(prodemo_votes) as prodemo_votes,
			sum(psr_votes) as psr_votes,
			sum(psdi_votes) as psdi_votes,
			sum(pru_votes) as pru_votes,
			sum(unpr_votes) as unpr_votes,
			sum(bun_votes) as bun_votes,
			sum(tudoran_votes) as tudoran_votes,
			sum(simion_votes) as simion_votes,
			sum(costea_votes) as costea_votes
			from sections
			"
		);

		return [
				'totalVotes' => $totalVotes,
				'psd_votes' => $partyTotals->psd_votes,
				'pnl_votes' => $partyTotals->pnl_votes,
				'usr_votes' => $partyTotals->usr_votes,
				'alde_votes' => $partyTotals->alde_votes,
				'proromania_votes' => $partyTotals->proromania_votes,
				'pmp_votes' => $partyTotals->pmp_votes,
				'udmr_votes' => $partyTotals->udmr_votes,
				'prodemo_votes' => $partyTotals->prodemo_votes,
				'psr_votes' => $partyTotals->psr_votes,
				'psdi_votes' => $partyTotals->psdi_votes,
				'pru_votes' => $partyTotals->pru_votes,
				'unpr_votes' => $partyTotals->unpr_votes,
				'bun_votes' => $partyTotals->bun_votes,
				'tudoran_votes' => $partyTotals->tudoran_votes,
				'simion_votes' => $partyTotals->simion_votes,
				'costea_votes' => $partyTotals->costea_votes,
			];
	}

	public static function judetElectionCount($judetId) {
		$judetId = intval($judetId);
		$totalRow = DB::selectOne("select sum(total_votes) as total_votes from sections where judet_id=$judetId");
		$totalVotes = $totalRow->total_votes;
		
		$partyTotals = DB::selectOne(
			"
			select sum(psd_votes) as psd_votes,
			sum(pnl_votes) as pnl_votes,
			sum(usr_votes) as usr_votes,
			sum(alde_votes) as alde_votes,
			sum(proromania_votes) as proromania_votes,
			sum(pmp_votes) as pmp_votes,
			sum(udmr_votes) as udmr_votes,
			sum(prodemo_votes) as prodemo_votes,
			sum(psr_votes) as psr_votes,
			sum(psdi_votes) as psdi_votes,
			sum(pru_votes) as pru_votes,
			sum(unpr_votes) as unpr_votes,
			sum(bun_votes) as bun_votes,
			sum(tudoran_votes) as tudoran_votes,
			sum(simion_votes) as simion_votes,
			sum(costea_votes) as costea_votes
			from sections
			where judet_id=$judetId
			"
		);

		return [
				'totalVotes' => $totalVotes,
				'psd_votes' => $partyTotals->psd_votes,
				'pnl_votes' => $partyTotals->pnl_votes,
				'usr_votes' => $partyTotals->usr_votes,
				'alde_votes' => $partyTotals->alde_votes,
				'proromania_votes' => $partyTotals->proromania_votes,
				'pmp_votes' => $partyTotals->pmp_votes,
				'udmr_votes' => $partyTotals->udmr_votes,
				'prodemo_votes' => $partyTotals->prodemo_votes,
				'psr_votes' => $partyTotals->psr_votes,
				'psdi_votes' => $partyTotals->psdi_votes,
				'pru_votes' => $partyTotals->pru_votes,
				'unpr_votes' => $partyTotals->unpr_votes,
				'bun_votes' => $partyTotals->bun_votes,
				'tudoran_votes' => $partyTotals->tudoran_votes,
				'simion_votes' => $partyTotals->simion_votes,
				'costea_votes' => $partyTotals->costea_votes,
			];
	}

	public static function exportByLoginStatus($loginStatus, $judetId=null) {
		$loginAtWhere = "(1=1)";
		$judetIdWhere = "(1=1)";
		if ($loginStatus == 'LOGGED_IN') {
			$loginAtWhere = "(observers.login_at is not null)";
		} else {
			$loginAtWhere = "(observers.login_at is null)";
		}

		if (!empty($judetId)) {
			$judetIdWhere = "(sections.judet_id=$judetId)";
		}

		$where = "$loginAtWhere and $judetIdWhere";

		$query = "
		select sections.*, observers.family_name, observers.given_name, observers.phone, judete.name as judet_name from observers
		join sections on observers.section_id=sections.id
		join judete on judete.id=sections.judet_id
		where $where
		";

		return DB::select($query);
	}

}

?>
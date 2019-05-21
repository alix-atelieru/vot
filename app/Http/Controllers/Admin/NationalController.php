<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;
use App\Model\Judet;
use App\Model\Pagination;
use App\Model\Question;
use App\Functions\DT;
use App\Model\Section;

class NationalController extends AdminController {
	public function dieIfBadType() {
		if (empty($this->admin())) {
			echo 'Access denied';
			die;
		}

		if ($this->admin()->type != Admin::TYPE_NATIONAL) {
			echo 'Access denied';
			die;
		}
	}

	public function observersActionShow(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		
		return $this->observersFilteredShow($request);
	}

	public function sectionsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return $this->sectionsFilteredShow($request);
	}

	/*
	observatorii la nivel national;
	*/
	public function observersStatsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return $this->observersStats($request);
	}
	
	public function getQuizesFilter($requestDict) {
		$filter = [];
		if (!empty($requestDict['judet_id'])) {
			$filter['judet_id'] = intval($requestDict['judet_id']);
		}
		
		return $filter;
	}
	
	public function quizesAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		$requestDict = $request->all();
		$filter = $this->getQuizesFilter($requestDict);
		return $this->quizes($requestDict, $filter);
	}

	/*
	ne trebe numarul total de voturi, gen sum(total_votes)
	apoi avem sum(voturi_psd) etc
	*/
	public function countNationalElectionAction(Request $request) {
		$nationalElectionTotals = Section::countNationalElection();
		return view('national/national_election_results', ['nationalElectionTotals' => $nationalElectionTotals]);
	}

	public function countJudetElectionAction(Request $request) {
		$judete = Judet::orderBy('name', 'asc')->get();
		foreach ($judete as &$judet) {
			$judet->votesCount = Section::judetElectionCount($judet->id);
		}

		return view('national/election_judet_count', ['judete' => $judete]);
	}

	public function exportCountJudetElectionAction() {
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=judete.csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$judete = Judet::orderBy('name', 'asc')->get();
		foreach ($judete as &$judet) {
			$judet->votesCount = Section::judetElectionCount($judet->id);
		}

		$f = fopen('php://output', 'w');
		$counterFields = Section::getCounterFields();
		$fieldsLabels = ['Judet', 'Total voturi'];
		$fieldsLabels = array_merge($fieldsLabels, array_column($counterFields, 'label'));
		$fieldsKeys = ['totalVotes'];
		$fieldsKeys = array_merge($fieldsKeys, array_column($counterFields, 'field'));
		fputcsv($f, $fieldsLabels, ',');
		foreach ($judete as $judet) {
			$values = [$judet->name];
			foreach ($fieldsKeys as $key) {
				$values[] = $judet->votesCount[$key];
			}

			fputcsv($f, $values, ",");
		}
		
		fclose($f);
	}

}





























?>
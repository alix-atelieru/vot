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
use Illuminate\Support\Facades\DB;

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
		$userType = $this->admin()->type;
		return view('national/election_judet_count', ['judete' => $judete, 'userType' => $userType]);
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

	public function showReferendumUpdateAction(Request $request, $sectionId) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return $this->showReferendumUpdate($request, $sectionId);
	}


	public function referendumUpdateAction(Request $request, $sectionId) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return $this->referendumUpdate($request, $sectionId);
	}

	public function exportSectionsByloginStatusAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=sectii.csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$requestDict = $request->all();
		$loginStatus = 'LOGGED_IN';
		if (!empty($requestDict['logged_in_status'])) {
			$loginStatus = $requestDict['logged_in_status'];
		}

		$sections = Section::exportByLoginStatus($loginStatus);
		$f = fopen('php://output', 'w');
		fputcsv($f, ['Judet', 'Nr sectie', 'Nume observator', 'Telefon observator']);
		foreach ($sections as $section) {
			fputcsv($f, [$section->judet_name, $section->nr, $section->family_name . ' ' . $section->given_name, $section->phone]);
		}
		fclose($f);
	}

	//tre sa luam toate datele;
	public function sectionAction(Request $request) {
		$judete = Judet::orderBy('name', 'asc')->get();
		$requestDict = $request->all();
		$section = null;
		$observer = null;
		if (!empty($requestDict['section_id'])) {
			$section = Section::find($requestDict['section_id']);
			$observer = Observer::where('section_id', $requestDict['section_id'])->first();
		}

		$judetSections = [];
		$judetName = '';
		$judet = null;
		if (!empty($requestDict['judet_id'])) {
			$judetSections = Section::where('judet_id', $requestDict['judet_id'])->get();
			$judet = Judet::find($requestDict['judet_id']);
			$judetName = $judet->name;
		}

		$counterFieldsLabels = array_column(Section::getCounterFields(), 'label');
		$counterFieldsKeys = array_column(Section::getCounterFields(), 'field');

		//print_r($section->psd_votes);

		return view('national/section', ['judete' => $judete, 
										 'judet_name' => $judetName,
										 'requestDict' => $requestDict, 
										 'judetSections' => $judetSections,
										 'counterFieldsLabels' => $counterFieldsLabels,
										 'counterFieldsKeys' => $counterFieldsKeys,
										 'userType' => $this->admin()->type,
										 'observer' => $observer,
										 'section' => $section]);
	}
}





























?>
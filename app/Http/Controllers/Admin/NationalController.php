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

	public function sectionAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		
		$judete = Judet::orderBy('name', 'asc')->get();
		$requestDict = $request->all();
		$section = null;
		$observer = null;
		$answers = null;


		$counterFieldsLabels = array_column(Section::getCounterFields(), 'label');
		$counterFieldsKeys = array_column(Section::getCounterFields(), 'field');

		if (!empty($requestDict['filter_type'])) {
			if ($requestDict['filter_type'] != 'by_judet_section' && $requestDict['filter_type'] != 'by_phone') {
				return 'Filtru gresit';
			}
		}

		if (empty($requestDict['filter_type'])) {
			$judetName = '';

			return view('national/section', ['judete' => $judete, 
											 'judet_name' => '',
											 'requestDict' => $requestDict, 
											 'judetSections' => [],
											 'questions' => [],
											 'answers' => [],
											 'counterFieldsLabels' => $counterFieldsLabels,
											 'counterFieldsKeys' => $counterFieldsKeys,
											 'userType' => $this->admin()->type,
											 'observer' => null,
											 'section' => null]);

		}

		if ($requestDict['filter_type'] == 'by_judet_section') {
			if (!empty($requestDict['section_id'])) {
				$section = Section::find($requestDict['section_id']);
				$observer = Observer::where('section_id', $requestDict['section_id'])->first();
				if (empty($observer)) {
					return 'Nu exista observator pe aceasta sectie';
				}
				$qa = $observer->getAnswers();
				//$answers = $observer->getAnswers();
			}

			$judetSections = [];
			$judetName = '';
			$judet = null;
			if (!empty($requestDict['judet_id'])) {
				//$judetSections = Section::where('judet_id', $requestDict['judet_id'])->get();
				$judetSections = Section::where('judet_id', $requestDict['judet_id'])->orderBy('nr', 'asc')->get();
				$judet = Judet::find($requestDict['judet_id']);
				$judetName = $judet->name;
			}

			return view('national/section', ['judete' => $judete, 
											 'judet_name' => $judetName,
											 'requestDict' => $requestDict, 
											 'judetSections' => $judetSections,
											 //'questions' => Question::orderBy('position', 'asc')->get(),
											 //'answers' => $answers,
											 'qa' => $qa,
											 'counterFieldsLabels' => $counterFieldsLabels,
											 'counterFieldsKeys' => $counterFieldsKeys,
											 'userType' => $this->admin()->type,
											 'observer' => $observer,
											 'section' => $section]);
		}

		$observer = Observer::where('phone', $requestDict['phone'])->first();
		if (empty($observer)) {
			return 'Telefonul nu exista';
		}

		if (empty($observer->judet_id) || empty($observer->section_id)) {
			return 'Observatorul nu are judet sau sectie';
		}

		$judet = Judet::find($observer->judet_id);
		if (empty($judet)) {
			return 'Judetul nu fu gasit';
		}

		$judetName = $judet->name;
		$judetSections = [];
		$answers = $observer->getAnswers();
		$section = Section::find($observer->section_id);
		
		return view('national/section', ['judete' => $judete, 
										 'judet_name' => $judetName,
										 'requestDict' => $requestDict, 
										 'judetSections' => $judetSections,
										 'questions' => Question::orderBy('position', 'asc')->get(),
										 'answers' => $answers,
										 'counterFieldsLabels' => $counterFieldsLabels,
										 'counterFieldsKeys' => $counterFieldsKeys,
										 'userType' => $this->admin()->type,
										 'observer' => $observer,
										 'section' => $section]);

	}

	public function createNationalAccountShowAction() {
		//print_r(DT::now());
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return view('national/create_national_account', ['adminNational' => new Admin()]);
	}

	public function createNationalAccountAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$response = Admin::addNational($request->all(), DT::now());
		if (!empty($response['ok'])) {
			return redirect()->route('national.account.create.national.show')->with('success', 'Creat');
		} else {
			return redirect()->route('national.account.create.national.show')->with('errorLabel', $response['errorLabel']);
		}
	}

	public function createJudetAccountShowAction() {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return view('national/create_judet_account', ['judete' => Judet::orderBy('name', 'asc')->get(), 'userType' => $this->admin()->type]);
	}

	public function createJudetAccountAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$response = Admin::addJudet($request->all(), DT::now());
		if (!empty($response['ok'])) {
			return redirect()->route('national.account.create.judet.show')->with('success', 'Creat');
		} else {
			return redirect()->route('national.account.create.judet.show')->with('errorLabel', $response['errorLabel']);
		}
	}

	public function adminsNationalAction() {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$adminsNational = Admin::where('type', Admin::TYPE_NATIONAL)->orderBy('username', 'asc')->get();
		return view('national/admins_national', ['adminsNational' => $adminsNational]);
	}

	public function adminsNationalDeleteActionAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		if (empty($requestDict['id'])) {
			return redirect()->route('national.admins.show')->with('errorLabel', 'Lipsa admin de sters');
		}

		$admin = Admin::find($requestDict['id']);
		if (empty($admin)) {
			return redirect()->route('national.admins.show')->with('errorLabel', 'User inexistent');
		}

		$admin->delete($requestDict['id']);

		return redirect()->route('national.admins.show')->with('success', 'Sters');
	}

	//ia tot create;
	public function updateNationalAccountShowAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		if (empty($requestDict['id'])) {
			return 'eroare';
		}
		$adminNational = Admin::find($requestDict['id']);
		if (empty($adminNational)) {
			return 'User inexistent';
		}

		return view('national/create_national_account', ['adminNational' => $adminNational]);
	}

	public function updateNationalAccountAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		//print_r($requestDict);die;
		if (empty($requestDict['id'])) {
			return 'eroare';
		}

		$adminNational = Admin::find($requestDict['id']);
		if (empty($adminNational)) {
			return 'User inexistent';
		}

		//daca avem parola->reseteaza si parola;daca se schimba intr-un username care exista?
		if (empty($requestDict['username']) || empty($requestDict['full_name'])) {
			return redirect()->route('national.account.update.national.show', ['id' => $requestDict['id']])->with('errorLabel', 'Campuri lipsa');
		}

		if (!empty($requestDict['password'])) {
			$adminNational->password = Admin::hashPassword($requestDict['password']);
		}
		
		$adminWithNewUsername = Admin::where('username', $requestDict['username'])->first();
		if (!empty($adminWithNewUsername)) {
			if ($adminWithNewUsername->id != $adminNational->id) {
				return redirect()->route('national.account.update.national.show', ['id' => $requestDict['id']])->with('errorLabel', 'Username exista');
			}
		}

		$adminNational->username = $requestDict['username'];
		$adminNational->full_name = $requestDict['full_name'];
		$adminNational->save();
		return redirect()->route('national.account.update.national.show', ['id' => $requestDict['id']])->with('success', 'Username actualizat');
	}

	public function adminsJudetAction(Request $request) {
		$adminsJudet = Admin::where('type', Admin::TYPE_JUDET)->orderBy('judet_id', 'asc')->orderBy('username', 'asc')->get();
		//print_r($adminsJudet);die;
		return view('national/admins_judet', ['adminsJudet' => $adminsJudet]);
	}


	public function updateJudetAccountShowAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();

		$judetAdmin = Admin::find($requestDict['id']);
		if (empty($judetAdmin)) {
			return 'User inexistent';
		}

		return view('national/create_judet_account', 
					[
						'judete' => Judet::orderBy('name', 'asc')->get(),
						'judetAdmin' => $judetAdmin
					]);
	}

	public function updateJudetAccountAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();

		$judetAdmin = Admin::find($requestDict['id']);
		if (empty($judetAdmin)) {
			return 'User inexistent';
		}	

		if (empty($requestDict['username']) || empty($requestDict['judet_id']) || empty($requestDict['full_name'])) {
			return redirect()->route('national.account.update.judet.show', ['id' => $requestDict['id']])->with('errorLabel', 'Camp lipsa');
		}

		if (!empty($requestDict['password'])) {
			$judetAdmin->password = Admin::hashPassword($requestDict['password']);
		}
		
		$adminWithNewUsername = Admin::where('username', $requestDict['username'])->first();
		if (!empty($adminWithNewUsername)) {
			if ($adminWithNewUsername->id != $judetAdmin->id) {
				return redirect()->route('national.account.update.judet.show', ['id' => $requestDict['id']])->with('errorLabel', 'Username exista');
			}
		}

		$judetAdmin->username = $requestDict['username'];
		$judetAdmin->judet_id = $requestDict['judet_id'];
		$judetAdmin->full_name = $requestDict['full_name'];
		$judetAdmin->save();

		return redirect()->route('national.account.update.judet.show', ['id' => $requestDict['id']])->with('success', 'Cont actualizat');
	}

	public function adminsJudetDeleteActionAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();

		$judetAdmin = Admin::find($requestDict['id']);
		if (empty($judetAdmin)) {
			return 'User inexistent';
		}

		$judetAdmin->delete();

		return redirect()->route('judet.admins.show')->with('success', 'Sters.');
	}

	public function sectionAddShowAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return view('national/section_add', ['judete' => Judet::orderBy('name', 'asc')->get()]);
	}

	public function sectionAddAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		if (empty($requestDict['judet_id']) || empty($requestDict['nr'])) {
			return 'date lipsa';
		}

		$sectionDup = Section::where('judet_id', $requestDict['judet_id'])->where('nr', intval($requestDict['nr']))->first();
		if (!empty($sectionDup)) {
			return 'Sectia exista';
		}
		
		$section = new Section();
		$section->judet_id = $requestDict['judet_id'];
		$section->nr = $requestDict['nr'];
		$section->adress = '';
		$section->save();
		if (empty($requestDict['full_name'])) {
			return redirect()->route('national.section.add.show');
		}
		//daca sectia nu are observatori, adauga un observator?
		
		$observer = new Observer();
		$observer->judet_id = $requestDict['judet_id'];
		$observer->section_id = $section->id;
		$observer->given_name = $requestDict['full_name'];
		$observer->created_at = DT::now();
		$observer->save();
		return redirect()->route('national.section.add.show');
	}

	public function quizExportAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();

		return $this->quizExport($requestDict);
	}

}





























?>
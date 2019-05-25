<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;
use App\Model\Judet;
use App\Model\Section;
use App\Model\Pagination;
use App\Model\Question;
use App\Model\Message;
use App\Functions\DT;


class JudetController extends AdminController {
	public function dieIfBadType() {
		if (empty($this->admin())) {
			echo 'Access denied';
			die;
		}
		
		if ($this->admin()->type != Admin::TYPE_JUDET) {
			echo 'Access denied';
			die;
		}
	}

	//ia judetul de care se ocupa;
	public function observersActionShow(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$judetId = $this->admin()->judet_id;
		if (empty($judetId)) {
			return 'Nu ai niciun judet';
		}

		$requestDict = $request->all();
		$page = $this->getPage($requestDict);
		$filter = ['judet_id' => $judetId];
		if (!empty($requestDict['activity'])) {
			$filter['activity'] = $requestDict['activity'];
		}

		$itemsPerPage = 100;
		$observers = Observer::listForAdminSelect($filter, $page, $itemsPerPage);
		$observersCount = Observer::listForAdminCount($filter);
		$type = $this->admin()->type;
		$pagesCount = Pagination::pagesCount($observersCount, $itemsPerPage);
		$nextPageUrl = $this->getNextPageUrl(route("$type.observers.show"), $requestDict, $page, $pagesCount);
		$prevPageUrl = $this->getPrevPageUrl(route("$type.observers.show"), $requestDict, $page);

		return view("$type/observers", 
					['observers' => $observers, 
					'userType' => $type,
					'observersCount' => $observersCount, 
					'requestDict' => $requestDict,
					'page' => $page,
					'pagesCount' => $pagesCount,
					'prevPageUrl' => $prevPageUrl,
					'nextPageUrl' => $nextPageUrl,
					'loginCount' => Observer::countLoginsInJudet($judetId),
					'judete' => Judet::orderBy('name', 'asc')->get()]);


		/*
		return view('judet/observers', ['observers' => $observers, 
										'observersCount' => $observersCount,
										'userType' => Admin::TYPE_JUDET, 
										'loginCount' => Observer::countLoginsJudet($judetId),

										'requestDict' => $requestDict]);
		*/
	}

	/*
	public function updateObserverShow(Request $request, $id) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$observer = Observer::find($id);
		$judetSections = [];
		if (!empty($observer->judet)) {
			$judetSections = $observer->judet->sections;
		}


		return view('judet/observer_update', 
					['observer' => $observer, 
					'judete' => Judet::orderBy('name', 'asc')->get(),
					'judetSections' => $judetSections
					]
					);
	}

	public function updateObserver(Request $request, $id) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$observer = Observer::find($id);
		$requestDict = $request->all();
		$response = $observer->updateByJudetAction($requestDict);
		if ($response['ok']) {
			return redirect()->route('judet.observer.update.show', ['id' => $id])->with('success', 'Date observator salvate');
		} else {
			return redirect()->route('judet.observer.update.show', ['id' => $id])->with('error', $response['error']);
		}
	}
	*/


	public function sectionsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		$admin = $this->admin();
		if (empty($admin)) {
			return 'Cont inexistent';
		}
		if (empty($admin->judet_id)) {
			return 'Nu ai judet';
		}

		$page = $this->getPage($requestDict);
		$filter = ['judet_id' => $this->admin()->judet_id];
		$sections = Section::paginatedAll($page, env('ITEMS_PER_PAGE'), $filter);
		$sectionsCount = Section::paginatedAllCount($filter);
		$pagesCount = Pagination::pagesCount($sectionsCount, env('ITEMS_PER_PAGE'));
		$nextPageUrl = $this->getNextPageUrl(route("judet.sections.show"), $requestDict, $page, $pagesCount);
		$prevPageUrl = $this->getPrevPageUrl(route("judet.sections.show"), $requestDict, $page);
		$counterFieldsLabels = array_column(Section::getCounterFields(), 'label');
		$counterFieldsKeys = array_column(Section::getCounterFields(), 'field');
		return view('judet/sections', [
			'page' => $page,
			'userType' => 'judet',
			'sections' => $sections,
			'counterFieldsLabels' => $counterFieldsLabels,
			'counterFieldsKeys' => $counterFieldsKeys,
			'requestDict' => $requestDict,
			'pagesCount' => $pagesCount,
			'prevPageUrl' => $prevPageUrl,
			'nextPageUrl' => $nextPageUrl,
			'sectionsCount' => $sectionsCount,
		]);
	}

	public function observersStatsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		$filter['judet_id'] = $this->admin()->judet_id;
		$observersCount = Observer::where('judet_id', $filter['judet_id'])->count();
		
		$loggedInPercentage = 0.00;
		$completedQuizPercentage = 0.00;
		$addedCountPercentage = 0.00;
		
		if ($observersCount > 0) {
			$loggedInCount = Observer::loginsCount($filter);
			$completedQuizCount = Observer::completedQuizCount($filter);
			$addedCountCount = Observer::addedCountCount($filter);
			
			$loggedInPercentage = 100*round($loggedInCount/$observersCount, 2);
			$completedQuizPercentage = 100*round($completedQuizCount/$observersCount, 2);
			$addedCountPercentage = 100*round($addedCountCount/$observersCount, 2);
		}

		return view('judet/observers_stats', 
			[
				'loggedInPercentage' => $loggedInPercentage, 
				'completedQuizPercentage' => $completedQuizPercentage, 
				'addedCountPercentage' => $addedCountPercentage
			]);
	}

	public function getQuizesFilter() {
		return ['judet_id' => $this->admin()->judet_id];
	}

	/*
	tre sa luam pagina+numarul de obs care au comletat quizul
	*/
	public function quizesAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		if (empty($this->admin()->judet_id)) {
			return 'Nu ai judet';
		}

		$requestDict = $request->all();
		$filter = $this->getQuizesFilter();
		return $this->quizes($requestDict, $filter);
	}

	public function showMessageAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		if (empty($this->admin()->judet_id)) {
			return 'Nu ai judet';
		}

		$requestDict = $request->all();

		$message = Message::findForJudet($this->admin()->judet_id);
		if (empty($message)) {
			$message = new Message();
		}
		return view("judet/show_message", ['message' => $message]);
	}

	public function upsertMessageAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		if (empty($this->admin()->judet_id)) {
			return 'Nu ai judet';
		}

		$requestDict = $request->all();
		$requestDict['admin_id'] = $this->admin()->id;
		$requestDict['judet_id'] = $this->admin()->judet_id;
		$response = Message::createForJudetAction($requestDict, DT::now());
		
		if (!$response['ok']) {
			return redirect()->route('judet.message')->with('error', $response['errorLabel']);	
		} else {
			return redirect()->route('judet.message')->with('success', 'Success');
		}
	}

	public function showReferendumUpdateAction(Request $request, $sectionId) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		if (empty($this->admin()->judet_id)) {
			return 'Nu ai judet';
		}

		$section = Section::find($sectionId);
		if (empty($section)) {
			return 'Sectia nu exista';
		}
		if ($this->admin()->judet_id != $section->judet_id) {
			return 'Acces interzis la aceasta sectie';
		}

		return view('judet/referendum', ['section' => $section]);
	}

	public function referendumUpdateAction(Request $request, $sectionId) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		if (empty($this->admin()->judet_id)) {
			return 'Nu ai judet';
		}

		$section = Section::find($sectionId);
		if (empty($section)) {
			return 'Sectia nu exista';
		}
		$admin = $this->admin();
		if ($admin->judet_id != $section->judet_id) {
			return 'Acces interzis la aceasta sectie';
		}

		//echo 'doing it';
		//Observer::saveRef($requestDict, $nr, Observer::TYPE_OBSERVER, $observer->id, $observer->section_id, DT::now());
		/*todo: vezi sa fie sectia corecta etc;*/
		$requestDict = $request->all();
		//echo $admin->type;
		$response = Observer::saveRef($requestDict, $requestDict['nr'], $admin->type, $admin->id, $section->id, DT::now());
		if ($response['ok'] == false) {
			return redirect()->route('judet.referendum.update.show', ['sectionId' => $sectionId])->with('error', $response['errorLabel']);
		} else {
			return redirect()->route('judet.referendum.update.show', ['sectionId' => $sectionId])->with('success', 'Salvat');
		}
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

		$sections = Section::exportByLoginStatus($loginStatus, $this->admin()->judet_id);
		$f = fopen('php://output', 'w');
		fputcsv($f, ['Judet', 'Nr sectie', 'Nume observator', 'Telefon observator']);
		foreach ($sections as $section) {
			fputcsv($f, [$section->judet_name, $section->nr, $section->family_name . ' ' . $section->given_name, $section->phone]);
		}
		fclose($f);
	}


	public function createJudetAccountShowAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return view('judet/judet_add', ['userType' => $this->admin()->type]);
	}

	public function createJudetAccountAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		$requestDict['judet_id'] = intval($this->admin()->judet_id);
		//print_r($this->admin()->judet_id);die;
		$response = Admin::addJudet($requestDict, DT::now());
		if (!empty($response['ok'])) {
			return redirect()->route('judet.account.create.judet.show')->with('success', 'Creat');
		} else {
			return redirect()->route('judet.account.create.judet.show')->with('errorLabel', $response['errorLabel']);
		}

		return view('judet/judet_add', ['userType' => $this->admin()->type]);
	}

	public function sectionAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		if (!empty($requestDict['filter_type'])) {
			return $this->sectionActionFiltered($request);
		}

		$judetSections = Section::where('judet_id', $this->admin()->judet_id)->orderBy('nr', 'asc')->get();
		$counterFieldsLabels = array_column(Section::getCounterFields(), 'label');
		$counterFieldsKeys = array_column(Section::getCounterFields(), 'field');
		//afiseaza toate sectiile
		return view('judet/section', ['judetSections' => $judetSections, 
									  'filtered' => false,
									  'requestDict' => $requestDict, ]);
	}

	public function sectionActionFiltered(Request $request) {
		$requestDict = $request->all();

		if ($requestDict['filter_type'] == 'by_judet_section') {
			$observer = Observer::where('section_id', $requestDict['section_id'])->first();
		} elseif($requestDict['filter_type'] == 'by_phone') {
			$observer = Observer::where('phone', $requestDict['phone'])->first();
		} else {
			return 'filtru gresit';
		}

		$judetSections = Section::where('judet_id', $this->admin()->judet_id)->orderBy('nr', 'asc')->get();
		if (empty($observer)) {
			return 'Observatorul nu exista';
		}
		if (empty($observer->section_id)) {
			return 'Observatorul nu are sectie';
		}

		$answers = $observer->getAnswers();
		$counterFieldsLabels = array_column(Section::getCounterFields(), 'label');
		$counterFieldsKeys = array_column(Section::getCounterFields(), 'field');
		$section = Section::find($observer->section_id);

		$judet = Judet::find($observer->judet_id);
		$judetName = $judet->name;
		
		return view('judet/section', [
										'filtered' => true,
										'judet_name' => $judetName,
										'userType' => Admin::TYPE_JUDET,
										 'requestDict' => $requestDict, 
										 'judetSections' => $judetSections,
										 'qa' => $observer->getAnswers(),
										 'questions' => Question::orderBy('position', 'asc')->get(),
										 'answers' => $answers,
										 'counterFieldsLabels' => $counterFieldsLabels,
										 'counterFieldsKeys' => $counterFieldsKeys,
										 'observer' => $observer,
										 'section' => $section]);

	}

	public function accountsShowAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		if (empty($this->admin()->judet_id)) {
			return 'Nu ai judet';
		}

		$adminsJudet = Admin::where('type', Admin::TYPE_JUDET)->where('judet_id', $this->admin()->judet_id)->get();
		return view('judet/admins_judet', ['adminsJudet' => $adminsJudet]);
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


		$admin = $this->admin();
		if (empty($admin)) {
			return 'Access denied';
		}

		if (empty($admin->judet_id)) {
			return 'Nu ai judet';
		}

		if ($admin->judet_id != $judetAdmin->judet_id) {
			return 'Nu poti sa modifici acest cont judetean';
		}

		return view('judet/judet_add', 
					[
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

		$admin = $this->admin();
		if (empty($admin)) {
			return 'Access denied';
		}

		if (empty($admin->judet_id)) {
			return 'Nu ai judet';
		}

		if ($admin->judet_id != $judetAdmin->judet_id) {
			return 'Nu poti sa modifici acest cont judetean';
		}

		if (empty($requestDict['username']) || empty($requestDict['full_name'])) {
			return redirect()->route('judet.account.update.show', ['id' => $requestDict['id']])->with('errorLabel', 'Camp lipsa');
		}

		if (!empty($requestDict['password'])) {
			$judetAdmin->password = Admin::hashPassword($requestDict['password']);
		}
		
		$adminWithNewUsername = Admin::where('username', $requestDict['username'])->first();
		if (!empty($adminWithNewUsername)) {
			if ($adminWithNewUsername->id != $judetAdmin->id) {
				return redirect()->route('judet.account.update.show', ['id' => $requestDict['id']])->with('errorLabel', 'Username exista');
			}
		}

		$judetAdmin->username = $requestDict['username'];
		$judetAdmin->judet_id = $this->admin()->judet_id;
		$judetAdmin->full_name = $requestDict['full_name'];
		$judetAdmin->save();

		return redirect()->route('judet.account.update.show', ['id' => $requestDict['id']])->with('success', 'Cont actualizat');
	}

}
?>
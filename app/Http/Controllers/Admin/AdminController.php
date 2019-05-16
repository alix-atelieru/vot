<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
//use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Model\Observer;
use App\Model\Section;
use App\Model\Judet;
use App\Model\Admin\Admin;
use App\Model\Pagination;

class AdminController extends Controller {

	public function getLoggedInAdminInfo() {
		return ['id' => session('id'), 'type' => session('type')];
	}

	public function isLoggedIn() {
		$adminInfo = $this->getLoggedInAdminInfo();
		return !empty($adminInfo['id']);
	}

	public function admin() {
		if (!$this->isLoggedIn()) {
			return null;
		}

		$adminInfo = $this->getLoggedInAdminInfo();
		return Admin::find($adminInfo['id']);
	}

	public function loginActionShow(Request $request) {
		if ($this->isLoggedIn()) {
			$userId = session('id');
			$admin = Admin::find($userId);
			return redirect($this->getWelcomeUrlForType($admin->type));
		}
		return view('login/login');
	}

	public function redirectToLogin() {
		return redirect(route('admin.login.show'));
	}

	//todo: daca ruta nu e valida, returneaza loginul?
	public function getWelcomeUrlForType($type) {
		return route("$type.observers.show");
	}

	public function loginAction(Request $request) {
		$response = Admin::loginWithDictAction($request->all());
		if (empty($response['ok'])) {
			return redirect(route('admin.login.show'))->with('errorLabel', $response['errorLabel']);
		}

		session(['id' => $response['user']->id, 'type' => $response['user']->type]);
		return redirect($this->getWelcomeUrlForType($response['user']->type));
	}

	public function updateObserverShow(Request $request, $id) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$observer = Observer::find($id);
		$admin = $this->admin();
		if ($admin->type == Admin::TYPE_JUDET) {
			if (empty($admin->judet_id) || empty($observer->judet_id)) {
				return 'Adminul sau observatorul nu au judet';
			}
			
			if ($admin->judet_id != $observer->judet_id) {
				return 'Observator inaccesibil';
			}
		}

		$judetSections = [];
		if (!empty($observer->judet)) {
			$judetSections = $observer->judet->sections;
		}

		return view($this->admin()->type . "/observer_update", 
					['observer' => $observer, 
					'judete' => Judet::orderBy('name', 'asc')->get(),
					'judetSections' => $judetSections,
					'isJudet' => $admin->type == Admin::TYPE_JUDET
					]
					);
	}

	/*
	toate campurile sunt obligatorii;
	telefonul si pinul tre sa fie numerice;
	daca userul logat e judet si judetul observastorului difera de judetul userului logat da eroare->da eroare;
	*/
	public function updateObserver(Request $request, $id) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		//$this->dieIfBadType();
		$observer = Observer::find($id);
		$admin = $this->admin();
		if ($admin->type == Admin::TYPE_JUDET) {
			if (empty($admin->judet_id) || empty($observer->judet_id)) {
				return redirect()->route('observer.update.show', ['id' => $id])->with('error', 'Adminul sau observatorul nu au judet');
			}
			
			if ($admin->judet_id != $observer->judet_id) {
				return redirect()->route('observer.update.show', ['id' => $id])->with('error', 'Acest user e in alt judet');
			}
		}

		$requestDict = $request->all();
		$response = $observer->updateByJudetAction($requestDict);
		if ($response['ok']) {
			return redirect()->route('observer.update.show', ['id' => $id])->with('success', 'Date observator salvate');
		} else {
			return redirect()->route('observer.update.show', ['id' => $id])->with('error', $response['error']);
		}
	}


	public function adminObserversActionShow(Request $request) {
		return 'listare nationala';
	}

	public function judetSectionsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return ['ok' => false, 'error' => 'NOT_LOGGEDIN', 'errorLabel' => 'Nu esti logat'];
		}

		$requestDict = $request->all();
		if (empty($requestDict['judet_id'])) {
			return ['ok' => false, 'error' => 'MISSING_PARAM', 'errorLabel' => 'Parametru lipsa'];
		}

		$judet = Judet::find($requestDict['judet_id']);
		if (empty($judet)) {
			return ['ok' => false, 'error' => 'JUDET_NOT_FOUND', 'errorLabel' => 'Judetul nu exista'];
		}

		return ['ok' => true, 'sections' => $judet->sections];
	}

	public function observersFilteredShow(Request $request) {
		$requestDict = $request->all();
		$page = $this->getPage($requestDict);
		
		$filter = $requestDict;
		if (!empty($filter['judet_id'])) {
			if ($filter['judet_id'] == 'ALL') {
				unset($filter['judet_id']);
			} elseif ($filter['judet_id'] == 'NOT_COMPLETED') {
				$filter['judet_id'] = null;
			}
		}
		
		$observers = Observer::listForAdminSelect($filter, $page, env('ITEMS_PER_PAGE'));
		$observersCount = Observer::listForAdminCount($filter);
		$pagesCount = Pagination::pagesCount($observersCount, env('ITEMS_PER_PAGE'));
		$type = $this->admin()->type;
		$nextPageUrl = $this->getNextPageUrl(route("$type.observers.show"), $requestDict, $page, $pagesCount);
		$prevPageUrl = $this->getPrevPageUrl(route("$type.observers.show"), $requestDict, $page);

		return view("$type/observers", 
					['observers' => $observers, 
					'observersCount' => $observersCount, 
					'requestDict' => $requestDict,
					'page' => $page,
					'pagesCount' => $pagesCount,
					'prevPageUrl' => $prevPageUrl,
					'nextPageUrl' => $nextPageUrl,
					'judete' => Judet::orderBy('name', 'asc')->get()]);

	}

	public function sectionsFilteredShow(Request $request) {
		$requestDict = $request->all();
		$page = $this->getPage($requestDict);
		
		$filter = $requestDict;
		if (!empty($filter['judet_id'])) {
			if ($filter['judet_id'] == 'ALL') {
				unset($filter['judet_id']);
			} elseif ($filter['judet_id'] == 'NOT_COMPLETED') {
				$filter['judet_id'] = null;
			}
		}

		$sections = Section::paginatedAll($page, env('ITEMS_PER_PAGE'), $filter);
		$sectionsCount = Section::paginatedAllCount($filter);
		$pagesCount = Pagination::pagesCount($sectionsCount, env('ITEMS_PER_PAGE'));
		$type = $this->admin()->type;
		$nextPageUrl = $this->getNextPageUrl(route("$type.sections.show"), $requestDict, $page, $pagesCount);
		$prevPageUrl = $this->getPrevPageUrl(route("$type.sections.show"), $requestDict, $page);
		$counterFieldsLabels = array_column(Section::getCounterFields(), 'label');
		$counterFieldsKeys = array_column(Section::getCounterFields(), 'field');
		//echo $pagesCount;
		return view("$type/sections", [
					'sections' => $sections,
					'counterFieldsLabels' => $counterFieldsLabels,
					'counterFieldsKeys' => $counterFieldsKeys,
					'sectionsCount' => $sectionsCount,
					'requestDict' => $requestDict,
					'page' => $page,
					'pagesCount' => $pagesCount,
					'prevPageUrl' => $prevPageUrl,
					'nextPageUrl' => $nextPageUrl,
					'judete' => Judet::orderBy('name', 'asc')->get()
				]);
	}

	public function sectionUpdateShow(Request $request, $id) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$admin = $this->admin();
		$userType = $admin->type;
		$section = Section::find($id);
		if ($userType == Admin::TYPE_JUDET) {
			if ($admin->judet_id != $section->judet_id) {
				return 'Nu ai dreptul sa vezi aceasta pagina';
			}
		}
		
		//$sectionCounterFields = ['psd_votes', 'usr_votes', 'alde_votes', 'proromania_votes', 'pmp_votes', 'udmr_votes'];
		$sectionCounterFields = array_column(Section::getCounterFields(), 'field');
		foreach ($sectionCounterFields as $field) {
			if (empty($section->{$field})) {
				$section->{$field} = 0;//poate pune 1 ca sa testam?
			}
		}
		//echo $id, ' ';
		return view($this->admin()->type . "/section_update", [
					'section' => $section,
					'counterFields' => Section::getCounterFields()
					]);
	}

	//verifica daca are dreptul sa faca modificari;verifica daca e logat;
	public function sectionUpdate(Request $request, $sectionId) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$admin = $this->admin();
		$userType = $admin->type;
		$requestDict = $request->all();
		$section = Section::find($sectionId);

		if ($userType == Admin::TYPE_JUDET) {
			if ($admin->judet_id != $section->judet_id) {
				return 'Nu ai dreptul sa vezi aceasta pagina';
			}
		}

		$updateResult = Section::addVotesCountAction($requestDict, $sectionId, $admin->id, $userType);
		if ($updateResult['ok'] == true) {
			return redirect()->route('section.update.show', ['id' => $sectionId])->with('success', 'Date sectie salvate');
		} else {
			return redirect()->route('section.update.show', ['id' => $sectionId])->with('error', $updateResult['errorLabel']);
		}
	}

}
?>
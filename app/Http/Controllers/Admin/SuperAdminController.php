<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;
use App\Model\Pagination;
use App\Model\Judet;
use App\Model\Job;
use App\Functions\DT;

class SuperAdminController extends AdminController {
	public function dieIfBadType() {
		if (empty($this->admin())) {
			echo 'Access denied';
			die;
		}

		if ($this->admin()->type != Admin::TYPE_SUPERADMIN) {
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

	public function massSmsActionShow(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();


		$filter = [];
		$filter['judete'] = Judet::orderBy('name', 'asc')->get();
		return view('superadmin/mass-sms', ['filter' => $filter]);
	}

	public function massSmsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		
		$requestDict = $request->all();
		$requestDict['created_by'] = $this->admin()->id;
		$creationStatus = Job::createAction($requestDict, DT::now());
		if ($creationStatus['ok'] == false) {
			//return route('superadmin.mass-sms.show')->redirect()->with('errorLabel', $creationStatus['errorLabel']);
			return redirect()->route('superadmin.mass-sms.show')->with('error', $creationStatus['errorLabel']);
		}
		return redirect()->route('superadmin.mass-sms.show')->with('success', 'Salvat');
	}

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

	public function addAdminShowAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$judete = Judet::orderBy('name', 'asc')->get();
		return view('superadmin/judet_add', ['judete' => $judete]);
	}

	/*
	daca se vrea si email?facem redirect inapoi cu mesajul cont adaugat
	todo: verfica daca e logat
	*/
	public function addAdminAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		$requestDict = $request->all();
		if ($requestDict['type'] == Admin::TYPE_JUDET) {
			$response = Admin::addJudet($requestDict, DT::now());
		} else {
			$response = Admin::addNational($requestDict, DT::now());
		}
		if ($response['ok'] == false) {
			return redirect(route('superadmin.admins.judet.add.show'))->with('errorLabel', $response['errorLabel']);
		}

		return redirect(route('superadmin.admins.judet.add'))->with('success', 'Cont creat');
	}

	/*
	ne trebuie:
	username, parola, tip, nume complet, judet
	tre sa afisam direct username,parola in browser;
	*/
	public function importAdminsAction(Request $request) {
		//Admin::importAdmins('/home/dev4a/public_html/vot/storage/admins.csv', DT::now());
		return 'xx';
	}

}
?>
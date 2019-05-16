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

}
?>
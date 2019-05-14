<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;
use App\Model\Judet;
use App\Model\Section;
use App\Model\Pagination;

class JudetController extends AdminController {
	public function dieIfBadType() {
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
		$filter = ['judet_id' => $judetId];//nu poate sa 
		$observers = Observer::listForAdminSelect($filter, $page, env('ITEMS_PER_PAGE'));
		$observersCount = Observer::listForAdminCount($filter);
		return view('judet/observers', ['observers' => $observers, 'observersCount' => $observersCount]);
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

	public function observersStatsAction(Request $request) {
		return 'aaa';
	}

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
		return view('judet/sections', [
			'page' => $page,
			'sections' => $sections,
			'requestDict' => $requestDict,
			'pagesCount' => $pagesCount,
			'prevPageUrl' => $prevPageUrl,
			'nextPageUrl' => $nextPageUrl,
		]);
	}

}
?>
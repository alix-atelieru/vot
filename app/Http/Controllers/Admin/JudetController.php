<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;
use App\Model\Judet;

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
}
?>
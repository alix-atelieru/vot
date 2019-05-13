<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;
use App\Model\Judet;

class NationalController extends AdminController {
	public function dieIfBadType() {
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

		$requestDict = $request->all();
		$page = $this->getPage($requestDict);
		$filter = $requestDict;
		$observers = Observer::listForAdminSelect($filter, $page, env('ITEMS_PER_PAGE'));
		$observersCount = Observer::listForAdminCount($filter);

		return view('national/observers', ['observers' => $observers, 'observersCount' => $observersCount, 'judete' => Judet::orderBy('name', 'asc')->get()]);
	}
}
?>
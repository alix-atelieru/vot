<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;

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

		return 'listare din national pentru superadmin';
	}

}
?>
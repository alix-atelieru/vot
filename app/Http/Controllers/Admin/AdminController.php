<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;

class AdminController extends BaseController {

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

	public function adminObserversActionShow(Request $request) {
		return 'listare nationala';
	}
}
?>
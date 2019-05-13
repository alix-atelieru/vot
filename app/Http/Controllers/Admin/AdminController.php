<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;

class AdminController extends BaseController {
	public function loginActionShow(Request $request) {
		return view('login/login');
	}

	/*
	todo:verifica daca e logat si daca da, ia userul si trimite-l in welcome;
	*/
	public function loginAction(Request $request) {
		//print_r(session('id'));die;
		$response = Admin::loginWithDictAction($request->all());
		if (empty($response['ok'])) {
			return redirect(route('admin.login.show'))->with('errorLabel', $response['errorLabel']);
		}

		/*
		creeaza modelul din $response[user]si apoi fa redirect catre model->welcome;
		pt asta tre sa stiu ce sa intorc din loginWithAction;
		*/
		session(['id' => $response['user']->id]);

		return 'ok, salveaza in sesiune si redirect catre ruta de welcome a rolului';
	}
}
?>
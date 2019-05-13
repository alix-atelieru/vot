<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Model\Observer;

class ObserverController extends Controller {
	//todo: allow from remote
	public function loginAction(Request $request) {
		header('Access-Control-Allow-Origin: *');
		return response()->json(Observer::loginAction($request->all()));
	}
}
?>
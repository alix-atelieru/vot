<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Model\Observer;
use App\Model\Section;

class ObserverController extends Controller {
	//todo: allow from remote
	public function loginAction(Request $request) {
		header('Access-Control-Allow-Origin: *');
		return response()->json(Observer::loginAction($request->all()));
	}

	public function addSectionCount(Request $request) {
		header('Access-Control-Allow-Origin: *');

		$requestDict = $request->all();
		if (empty($requestDict['token']) || empty($requestDict['observer_id'])) {
			return response()->json(['ok' => false, 'error' => 'MISSING_PARAMS', 'errorLabel' => 'Eroare trimitere date']);
		}

		$tokenStatus = $this->checkSessionToken($requestDict['observer_id'], $requestDict['token']);
		if ($tokenStatus['ok'] === false) {
			return response()->json($tokenStatus);
		}
		//ne trebuie id-ul sectiei;
		$observer = Observer::find($requestDict['observer_id']);
		if (empty($observer)) {
			return response()->json(['ok' => false, 'error' => 'OBSERVER_NOT_FOUND', 'errorLabel' => 'Observatorul nu a fost gasit']);
		}

		if (empty($observer->section_id)) {
			return response()->json(['ok' => false, 'error' => 'SECTION_NOT_SELECTED', 'errorLabel' => 'Nu ai selectat o sectie']);
		}

		return response()->json(Section::addVotesCountAction($request->all(), $observer->section_id, $observer->id, Observer::TYPE_OBSERVER));
		//return 42;
	}

}
?>
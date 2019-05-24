<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Model\Observer;
use App\Model\Section;
use App\Functions\DT;
use App\Model\SMS;

class ObserverController extends Controller {
	public function loginAction(Request $request) {
		header('Access-Control-Allow-Origin: *');
		return response()->json(Observer::loginAction($request->all(), DT::now()));
	}

	public function tokenVerify($requestDict) {
		if (empty($requestDict['token']) || empty($requestDict['observer_id'])) {
			return ['ok' => false, 'error' => 'MISSING_PARAMS', 'errorLabel' => 'Eroare trimitere date'];
		}

		$tokenStatus = $this->checkSessionToken($requestDict['observer_id'], $requestDict['token']);
		if ($tokenStatus['ok'] === false) {
			return $tokenStatus;
		}
		//ne trebuie id-ul sectiei;
		$observer = Observer::find($requestDict['observer_id']);
		if (empty($observer)) {
			return ['ok' => false, 'error' => 'OBSERVER_NOT_FOUND', 'errorLabel' => 'Observatorul nu a fost gasit'];
		}

		if (empty($observer->section_id)) {
			return ['ok' => false, 'error' => 'SECTION_NOT_SELECTED', 'errorLabel' => 'Nu ai selectat o sectie'];
		}

		return ['ok' => true];
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
	}

	/*
	todo: verifica tokenul 
	*/
	public function quizAnswerAction(Request $request) {
		header('Access-Control-Allow-Origin: *');

		$requestDict = $request->all();
		$tokenVerification = $this->tokenVerify($requestDict);
		if ($tokenVerification['ok'] == false) {
			return response()->json($tokenVerification);
		}
		$observer = Observer::find($requestDict['observer_id']);
		return response()->json($observer->quizAnswer($request->all(), DT::now()));
	}

	public function sectionSelectAction(Request $request) {
		header('Access-Control-Allow-Origin: *');

		$requestDict = $request->all();
		$tokenVerification = $this->tokenVerify($requestDict);
		if ($tokenVerification['ok'] == false) {
			return response()->json($tokenVerification);
		}

		return response()->json(Observer::sectionSelect($requestDict, DT::now()));
	}

	public function saveRef(Request $request) {
		header('Access-Control-Allow-Origin: *');

		$requestDict = $request->all();
		$tokenVerification = $this->tokenVerify($requestDict);
		if ($tokenVerification['ok'] == false) {
			return response()->json($tokenVerification);
		}

		$observer = Observer::find($requestDict['observer_id']);
		if (empty($observer)) {
			return response()->json(['ok' => false, 'error' => 'OBSERVER_NOT_FOUND', 'errorLabel' => 'Observatorul nu a fost gasit']);
		}

		if (empty($observer->section_id)) {
			return response()->json(['ok' => false, 'error' => 'SECTION_NOT_SELECTED', 'errorLabel' => 'Nu ai selectat o sectie']);
		}

		if (empty($requestDict['nr'])) {
			return response()->json(['ok' => false, 'error' => 'MISSING_NR', 'errorLabel' => 'Numar lipsa']);
		}

		$nr = intval($requestDict['nr']);
		if ($nr != 1 && $nr != 2) {
			return response()->json(['ok' => false, 'error' => 'BAD_NR', 'errorLabel' => 'Numar gresit']);
		}
		
		$ref1SaveResponse = Observer::saveRef($requestDict, $nr, Observer::TYPE_OBSERVER, $observer->id, $observer->section_id, DT::now());
		return response()->json($ref1SaveResponse);
	}

	public function sendSMSAction(Request $request) {
		$requestDict = $request->all();
		$sms = SMS::createFromEnv();
		$phone = '4' . strval($requestDict['phone']);//e in ro??
		$sms->sendMessageTo($requestDict['message'], $phone);
		return ['ok' => true];
	}


	/*
	ia datele sectiei 
	*/
	public function votesAction(Request $request) {
		header('Access-Control-Allow-Origin: *');
		$requestDict = $request->all();
		$tokenVerification = $this->tokenVerify($requestDict);
		if ($tokenVerification['ok'] == false) {
			return response()->json($tokenVerification);
		}

		$observer = Observer::find($requestDict['observer_id']);
		if (empty($observer)) {
			return response()->json(['ok' => false, 'error' => 'OBSERVER_NOT_FOUND', 'errorLabel' => 'Observatorul nu a fost gasit']);
		}

		if (empty($observer->section_id)) {
			return response()->json(['ok' => false, 'error' => 'SECTION_NOT_SELECTED', 'errorLabel' => 'Nu ai selectat o sectie']);
		}

		$section = Section::find($observer->section_id);
		if (empty($section)) {
			return ['ok' => false, 'error' => "OBSERVER_WITHOUT_SECTION", 'errorLabel' => 'Nu ai sectie'];
		}

		return ['ok' => true, 'section' => $section];
	}


	public function quizAction(Request $request) {
		header('Access-Control-Allow-Origin: *');
		$requestDict = $request->all();
		$tokenVerification = $this->tokenVerify($requestDict);
		if ($tokenVerification['ok'] == false) {
			return response()->json($tokenVerification);
		}

		$observer = Observer::find($requestDict['observer_id']);
		if (empty($observer)) {
			return response()->json(['ok' => false, 'error' => 'OBSERVER_NOT_FOUND', 'errorLabel' => 'Observatorul nu a fost gasit']);
		}

		return $observer->getAnswers();
	}














}
?>
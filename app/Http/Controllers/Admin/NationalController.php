<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Model\Observer;
use App\Model\Admin\Admin;
use App\Model\Judet;
use App\Model\Pagination;
use App\Model\Question;

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
		
		return $this->observersFilteredShow($request);
	}

	public function sectionsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return $this->sectionsFilteredShow($request);
	}

	/*
	observatorii la nivel national;
	*/
	public function observersStatsAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();

		return $this->observersStats($request);
	}
	
	public function getQuizesFilter($requestDict) {
		$filter = [];
		if (!empty($requestDict['judet_id'])) {
			$filter['judet_id'] = intval($requestDict['judet_id']);
		}
		
		return $filter;
	}
	
	public function quizesAction(Request $request) {
		if (!$this->isLoggedIn()) {
			return $this->redirectToLogin();
		}

		$this->dieIfBadType();
		$requestDict = $request->all();
		$filter = $this->getQuizesFilter($requestDict);
		return $this->quizes($requestDict, $filter);
	}
}





























?>
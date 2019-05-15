<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Job extends Model {
	public $table = 'jobs';
	public $timestamps = false;

	const STATUS_NOT_RUNNING = 'NOT_RUNNING';
	const STATUS_RUNNING = 'RUNNING';
	const STATUS_DONE = 'DONE';

	public $fillable = ['created_at', 'created_by', 'judet_id', 'type', 'status', 'message'];

	public static function createAction($requestDict, $now) {
		if (empty($requestDict['created_by']) || empty($requestDict['judet_id']) || empty($requestDict['type']) || empty($requestDict['message'])) {
			return ['ok' => false, 'errorLabel' => 'Campuri lipsa'];
		}

		if (mb_strlen($requestDict['message'], 'UTF-8') > 100) {
			return ['ok' => false, 'errorLabel' => 'Mesaj prea lung'];
		}

		$requestDict['status'] = self::STATUS_NOT_RUNNING;
		$requestDict['created_at'] = $now;

		self::create($requestDict);
		return ['ok' => true];
	}

	public static function getJobsToRun() {
		return self::where('status', self::STATUS_NOT_RUNNING)->get();
	}

	/*
	ALL/NO_LOGIN/NO_QUIZ/NO_VOTES_COUNT_SENT
	test this method too.
	judet_id tre sa existe mereu
	*/
	public function getObservers() {
		if (empty($this->judet_id)) {
			$judet_id = 'ALL';
		} else {
			$judet_id = intval($this->judet_id);
		}

		$filter = [];
		if ($judet_id != 'ALL') {
			$filter['judet_id'] = intval($judet_id);
		}

		if ($this->type == 'ALL') {
			$observers = Observer::allToSms($filter);
		} elseif($this->type == 'NO_LOGIN') {
			$observers = Observer::noLogin($filter);
		}elseif($this->type == 'NO_QUIZ') {
			$observers = Observer::noQuiz($filter);
		} elseif ($this->type == 'NO_VOTES_COUNT_SENT') {
			$observers = Observer::noVotesCountSent($filter);
		}

		return $observers;
	}
}

?>
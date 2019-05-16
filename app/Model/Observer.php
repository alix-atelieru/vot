<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\SessionToken;
use App\Model\Judet;
use App\Model\Section;
use App\Model\Question;

class Observer extends Model {
	public $timestamps = false;
	
	const TYPE_OBSERVER = 'observer';

	public function judet() {
		return $this->belongsTo('App\Model\Judet');
	}

	public function section() {
		return $this->belongsTo('App\Model\Section');
	}

	public static function findByCredentials($phone, $pin) {
		return self::where('phone', $phone)->where('pin', $pin)->first();
	}

	//todo:intoarce si sectiile din judet
	public static function loginAction($dict, $now) {
		if (empty($dict['phone']) || empty($dict['pin'])) {
			return ['ok' => false, 'error' => 'MISSING_PARAMS', 'error_label' => 'Campuri lipsa'];
		}

		$observer = self::findByCredentials($dict['phone'], $dict['pin']);
		if (empty($observer)) {
			return ['ok' => false, 'error' => 'BAD_LOGIN', 'error_label' => 'Date login gresite'];
		}
		//all good
		$token = SessionToken::build();
		$token->observer()->associate($observer);
		$token->save();

		$observer->login_at = $now;
		$observer->save();
		
		//judetele alfabetic, sectiile nu conteaza, se rezolva din front;
		return ['ok' => true, 
				'token' => $token->token, 
				'id' => $observer->id,
				'questions' => Question::sortedAll(),
				'judete' => Judet::orderBy('name', 'ASC')->get(),
				'sectii' => Section::all(['id', 'judet_id', 'nr','adress', 'name'])
				];
	}

	/*
	next up->adaugare numaratoare sectie?aici nu am stabilit daca poate sa updateze and whatnot.
	sa facem logini pentru admini?
	atentie la includere css-uri;
	ce rute avem?
	admin/judetean/xxx;Admin/JudeteanController;
	admin/national;Admin/NationalController;
	/admin/admin;Admin/SuperAdminController;
	toti mostenesc din AdminController;
	*/

	/*
	lista care apare la administrare observatori;
	listForSmsQuery
	*/
	public static function listForAdminQueryBody() {
		return "
			from observers
			left join sections on observers.section_id=sections.id
			left join judete on observers.judet_id=judete.id
		";
	}

	public static function listForAdminQueryWhere($filter) {
		$judeteFilter = '(1=1)';
		if (array_key_exists('judet_id', $filter)) {
			if ($filter['judet_id'] === null) {
				$judeteFilter = "(observers.judet_id is null)";
			} else {
				$judetId = intval($filter['judet_id']);
				$judeteFilter = "(observers.judet_id=$judetId)";

			}
		}

		$whereFilter = " where " . $judeteFilter;
		return $whereFilter;
	}

	public static function listForAdminQueryLimit($currentPage, $itemsPerPage) {
		$offset = Pagination::offset($currentPage, $itemsPerPage);
		return " limit $offset, $itemsPerPage";
	}

	public static function listForAdminSelectQuery($filter, $currentPage, $itemsPerPage) {
		$q = "select observers.*,judete.name as judet_name, sections.nr as section_nr ";
		$q .= Observer::listForAdminQueryBody() . ' ';
		$q .= Observer::listForAdminQueryWhere($filter) . ' ';
		$q .= " order by observers.family_name asc";
		$q .= Observer::listForAdminQueryLimit($currentPage, $itemsPerPage);
		return $q;
	}

	public static function listForAdminCountQuery($filter) {
		$q = "select count(observers.id) as observers_count ";
		$q .= Observer::listForAdminQueryBody() . ' ';
		$q .= Observer::listForAdminQueryWhere($filter) . ' ';
		return $q;
	}

	public static function listForAdminSelect($filter, $currentPage, $itemsPerPage) {
		return DB::select(self::listForAdminSelectQuery($filter, $currentPage, $itemsPerPage));
	}

	public static function listForAdminCount($filter) {
		$row = DB::selectOne(self::listForAdminCountQuery($filter));
		if (!empty($row->observers_count)) {
			return $row->observers_count;
		}

		return 0;
	}

	public function phoneExists($phone) {
		$query = DB::raw("select * from observers where id != :observerId and phone = :phone");
		$rows = DB::select($query, ['observerId' => $this->id, 'phone' => $phone]);
		return !empty($rows);
	}

	//maybe wrap this in a transaction with a locked table?
	public function updateByJudetAction($requestDict) {
		$requiredFields = ['family_name', 'given_name', 'phone', 'pin'];
		foreach ($requiredFields as $field) {
			if (empty($requestDict[$field])) {
				return ['ok' => false, 'error' => 'Camp lipsa'];
			}
		}
		
		if (!preg_match('/^[0-9]+$/', $requestDict['phone'])) {
			return ['ok' => false, 'error' => 'Telefon invalid'];
		}

		if (!preg_match('/^[0-9]+$/', $requestDict['pin'])) {
			return ['ok' => false, 'error' => 'Pin invalid'];
		}

		DB::beginTransaction();
		try {
			DB::unprepared('lock tables observers WRITE');
			if ($this->phoneExists($requestDict['phone'])) {
				return ['ok' => false, 'error' => 'Telefonul exista'];
			}

			$this->family_name = $requestDict['family_name'];
			$this->given_name = $requestDict['given_name'];
			$this->phone = $requestDict['phone'];
			$this->pin = $requestDict['pin'];

			/*
			tre sa facem sectie/judet;
			*/
			if (!empty($requestDict['judet_id'])) {
				$this->judet_id = $requestDict['judet_id'];	
			}
			
			if (!empty($requestDict['section_id'])) {
				$this->section_id = $requestDict['section_id'];	
			} else {
				$this->section_id = null;
			}

			$this->save();
			DB::commit();
			DB::unprepared('unlock tables');
			return ['ok' => true];
		} catch(\Exception $e) {
			//print_r($e);
			DB::rollBack();
			return ['ok' => false, 'error' => 'Tranzactie esuata.Incearca din nou.'];
		}
	}
	
	public function deleteQuizAnswers() {
		DB::table("questions_answers")->where('observer_id', $this->id)->delete();
	}
	
	public function addAnswer($questionId, $answer) {
		DB::table('questions_answers')
		  ->insert(['observer_id' => $this->id, 
		  			'question_id' => $questionId,
		  			'answer' => $answer
		  			]);
	}
	
	/*
	 
	*/
	public function addAnswers($qa) {
		for($i = 0;$i < count($qa['question_id']);$i++) {
			//verifica sa fie egale?
			$this->addAnswer($qa['question_id'][$i], $qa['answer'][$i]);
		}
	}
	
	/*
	sterge toate raspunsurile daca exista
	updateaza quiz_last_updated_time
	*/
	public function quizAnswer($requestDict, $now) {
		if (empty($requestDict['question_id']) || empty($requestDict['answer'])) {
			return ['ok' => false, 'errorLabel' => 'Nu ai raspuns la nicio intrebare'];
		}
		
		if (count($requestDict['question_id']) != count($requestDict['answer'])) {
			return ['ok' => false, 'errorLabel' => 'Nepotrivire parametrii'];
		}
		
		DB::beginTransaction();
		try {
			$this->deleteQuizAnswers();
			$this->addAnswers($requestDict);
			$this->quiz_last_updated_datetime = $now;
			$this->save();
			DB::commit();
			return ['ok' => true];
		} catch(\Exception $e) {
			print_r($e);
			DB::rollBack();
			return ['ok' => false, 'errorLabel' => 'Tranzactie esuata.Incearca din nou.'];
		} 
	}

	public static function sectionSelect($requestDict, $now) {
		if (empty($requestDict['section_id']) || intval($requestDict['section_id']) == 0) {
			return ['ok' => false, 'error' => 'SECTION_MISSING', 'errorLabel' => 'Eroare trimite sectie.Incearca sa dai refresh'];
		}

		$observer = Observer::find($requestDict['observer_id']);
		if ($observer->section_id != $requestDict['section_id']) {
			return ['ok' => false, 'error' => 'OBSERVER_SELECTED_SECTION_MISMATCH', 'errorLabel' => 'Sectie incorecta'];
		}

		/*
		todo: poate numaram si cati sunt logati pe sectie;fa o tranzactie cu locking?

		*/
		$observer->selected_section_at = $now;
		$observer->save();
		return ['ok' => true];
	}

	public static function allToSms($filter) {
		if (empty($filter['judet_id'])) {
			return self::all();	
		} else {
			return self::where('judet_id', $filter['judet_id'])->get();
		}
	}

	/*
	ALL/NO_LOGIN/NO_QUIZ/NO_VOTES_COUNT_SENT
	*/
	public static function noLogin($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('login_at', null)->get();	
		} else {
			return self::where('login_at', null)->where('judet_id', $filter['judet_id'])->get();
		}
	}

	public static function noQuiz($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('quiz_last_updated_datetime', null)->get();	
		} else {
			return self::where('quiz_last_updated_datetime', null)->where('judet_id', $filter['judet_id'])->get();
		}
	}

	public static function sentQuiz($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('quiz_last_updated_datetime', '!=', null)->get();	
		} else {
			return self::where('quiz_last_updated_datetime', '!=', null)->where('judet_id', $filter['judet_id'])->get();
		}
	}

	public static function noVotesCountSent($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('counted_section_id_at', null)->get();	
		} else {
			return self::where('counted_section_id_at', null)->where('judet_id', $filter['judet_id'])->get();
		}
	}

	public static function votesCountSent($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('counted_section_id_at', '!=', null)->get();	
		} else {
			return self::where('counted_section_id_at', '!=', null)->where('judet_id', $filter['judet_id'])->get();
		}
	}

	public static function loginsCount($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('login_at', '!=', null)->count();
		} else {
			return self::where('judet_id', $filter['judet_id'])->where('login_at', '!=', null)->count();
		}
	}

	public static function completedQuizCount($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('quiz_last_updated_datetime', '!=', null)->count();
		} else {
			return self::where('judet_id', $filter['judet_id'])->where('quiz_last_updated_datetime', '!=', null)->count();
		}
	}	

	public static function addedCountCount($filter) {
		if (empty($filter['judet_id'])) {
			return self::where('counted_section_id_at', '!=', null)->count();
		} else {
			return self::where('judet_id', $filter['judet_id'])->where('counted_section_id_at', '!=', null)->count();
		}
	} 

	public static function completedQuizQueryBuild($filter=[]) {
		$build = Observer::where('quiz_last_updated_datetime', '!=', null);
		if (!empty($filter['judet_id'])) {
			$build->where('judet_id', $filter['judet_id']);
		}
		return $build;
	}

	public static function completedQuizQuery($filter, $page, $itemsPerPage) {
		$build = self::completedQuizQueryBuild($filter);
		$build->orderBy('quiz_last_updated_datetime', 'DESC');
		$build->take($itemsPerPage);
		$build->skip(Pagination::offset($page, $itemsPerPage));
		return $build;
	}

	public static function getQuizAnswers($observers) {
		if (empty($observers)) {
			return [];
		}
		
		$observersIds = [];
		foreach ($observers as $observer) {
			$observersIds[] = intval($observer->id);
		}

		$observersIdsCsv = implode(",", $observersIds);
		return DB::select("
			select questions_answers.*, questions.position, questions.content from questions_answers 
			join questions on questions.id=questions_answers.question_id
			where observer_id in ($observersIdsCsv)
			order by questions.position asc
			");
	}

	public static function matchObserversToAnswers($observers, $answers) {
		$matchedObservers = [];
		foreach ($observers as $observer) {
			$matchedObserver = clone $observer;
			$answersFound = [];
			foreach ($answers as $answer) {
				if ($observer->id == $answer->observer_id) {
					$answersFound[] = $answer;
				}
			}
			$matchedObserver->answers = $answersFound;
			$matchedObservers[] = $matchedObserver;
		}
		return $matchedObservers;
	}

	/*
	public static function completedQuizCount($filter) {
		$build = self::completedQuizQueryBuild($filter);
		return $build->count();
	}
	*/
}





















?>
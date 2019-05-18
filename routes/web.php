<?php
use App\Model\Admin\Admin;
use App\Model\Judet;
use App\Model\Observer;
use App\Model\Section;
use App\Model\Session;
use App\Model\SessionToken;
use App\Model\Pin;
use App\Model\Question;
use App\Model\Job;
use App\Functions\DT;
use App\Model\ObserversImport;

/*
sa facem loginul de observator;
loginul se face dupa telefon si pin
ne trebe o clasa pin care sa genereze pinuri
*/
Route::get("/", function() {
	/*
	$j = Judet::find(1);
	echo $j->name;
	
	$admin = new Admin();
	$admin->username = 'aa';
	$admin->password = 'abcd';
	$admin->full_name = "aa bb";
	$admin->created_at = DT::now();
	$admin->region_type = 'judet';
	$admin->is_super_admin = 0;
	$admin->judet()->associate($j);
	$admin->save();
	*/

	/*
	$o = new Observer();
	$o->section()->associate(Section::find(2));
	$o->judet()->associate(Judet::find(2));
	$o->created_at = DT::now();
	$o->save();
	*/

	/*
	$t = SessionToken::build();
	$o = Observer::find(1);
	$t->observer()->associate($o);
	$t->save();
	*/

	/*
	$pin = new Pin();
	echo $pin->generate();
	*/

	/*
	$o = Observer::findByCredentials('076', '123');
	print_r($o);
	//var_dump($o->isEmpty());
	*/

	//print_r(Observer::loginAction(['phone' => '076', 'pin' => 123]));
	
    return 'hi.';
});

Route::get("/xxyy", function() {
	/*
	echo Admin::hashPassword('abc');
	echo Admin::hashPassword('123');
	*/

	//print_r(Admin::findByCredentials('National1', '123'));

	//echo json_encode(Judet::orderBy('name', 'ASC')->get());

	/*
	$q = "select observers.*,judete.name as judet_name, sections.nr as section_nr ";
	$q .= Observer::listForAdminQueryBody() . ' ';
	$q .= Observer::listForAdminQueryWhere(['judet_id' => 2]) . ' ';
	$q .= Observer::listForAdminQueryLimit(1, 10);
	echo $q;
	*/

	//poate punem si null?
	//echo Observer::listForAdminSelectQuery([], 1, 10);
	
	/*
	print_r(Observer::listForAdminSelect(['judet_id' => 2], 1, 10));
	print_r(Observer::listForAdminCount(['judet_id' => 1]));
	*/

	//print_r(Section::paginatedAllCount(['judet_id' => 'aaa']));
	//print_r($sections);

	/*
	echo '<pre>';
	print_r(Question::sortedAll());
	echo '</pre>';
	die;
	*/
	
	
	/*
	foreach (Job::getJobsToRun() as $job) {
		$observers = $job->getObservers();
		foreach ($observers as $o) {
			echo $o->id, ' ';
		}
	}
	*/
	//$o->addAnswer(1,'da');
	
	/*
	$observers = Observer::votesCountSent(['judet_id' => 2]);
	foreach ($observers as $o) {
		echo $o->id, ' ';
	}
	*/

	//echo Observer::addedCountCount([]);

	$observersCompletedQuiz = Observer::completedQuizQuery([], 1, 20);
	$answersGivenByObservers = Observer::getQuizAnswers($observersCompletedQuiz->get());

	/*
	foreach ($answersGivenByObservers as $a) {
		echo $a->observer_id, ' ';
	}
	*/

	//ar fi bine daca raspunsurile ar fi ordonate dupa pozitiile intrebarilor;poate facem join dupa intrebari si ordonam dupa position?
	$matchedObservers = Observer::matchObserversToAnswers($observersCompletedQuiz->get(), $answersGivenByObservers);
	foreach ($matchedObservers as $mo) {
		echo '<pre>';
		print_r($mo->answers);
		echo '</pre>';
	}
	//print_r($observersCompletedQuiz);

	/*
	$observersCompletedQuizCount = Observer::completedQuizCount(['judet_id' => 2]);
	echo $observersCompletedQuizCount, '<br/>';

	foreach ($observersCompletedQuiz->get() as $o) {
		echo $o->id, ' ';
	}
	*/
	return view('index');
});

Route::post("/observer/login", 'ObserverController@loginAction')->name('observer.login');
Route::get('/admin/login', 'Admin\AdminController@loginActionShow')->name('admin.login.show');
Route::post('/admin/login', 'Admin\AdminController@loginAction')->name('admin.login');


Route::get('/judet/observers', 'Admin\JudetController@observersActionShow')->name('judet.observers.show');
Route::get('/national/observers', 'Admin\NationalController@observersActionShow')->name('national.observers.show');
Route::get('/superadmin/observers', 'Admin\SuperAdminController@observersActionShow')->name('superadmin.observers.show');

Route::get('/observer/update/{id}', 'Admin\AdminController@updateObserverShow')->name('observer.update.show');
Route::post('/observer/update/{id}', 'Admin\AdminController@updateObserver')->name('observer.update');

/*
Route::get('/national/observer/update/{id}', 'Admin\NationalController@updateObserverShow')->name('national.observer.update.show');
Route::post('/national/observer/update/{id}', 'Admin\NationalController@updateObserver')->name('national.observer.update');
*/

Route::get('/admin/judet/sections', 'Admin\AdminController@judetSectionsAction')->name('admin.judet.sections');

Route::post('/observer/add_section_count', 'ObserverController@addSectionCount')->name('observer.add_section_count');

Route::get('/judet/sections', 'Admin\JudetController@sectionsAction')->name('judet.sections.show');

Route::get('/national/sections', 'Admin\NationalController@sectionsAction')->name('national.sections.show');
Route::get('/superadmin/sections', 'Admin\SuperAdminController@sectionsAction')->name('superadmin.sections.show');

Route::get('/section/update/{id}', 'Admin\AdminController@sectionUpdateShow')->name('section.update.show');
Route::post('/section/update/{id}', 'Admin\AdminController@sectionUpdate')->name('section.update');

Route::post("/observer/quiz/answer", 'ObserverController@quizAnswerAction')->name('observer.quiz.answer');
Route::post("/observer/section/select", 'ObserverController@sectionSelectAction')->name('observer.section.select');

Route::get('/superadmin/mass-sms', 'Admin\SuperAdminController@massSmsActionShow')->name('superadmin.mass-sms.show');
Route::post('/superadmin/mass-sms', 'Admin\SuperAdminController@massSmsAction')->name('superadmin.mass-sms');

Route::get('/judet/observers/stats', 'Admin\JudetController@observersStatsAction')->name('judet.observers.stats');
Route::get('/national/observers/stats', 'Admin\NationalController@observersStatsAction')->name('national.observers.stats');
Route::get('/superadmin/observers/stats', 'Admin\SuperAdminController@observersStatsAction')->name('superadmin.observers.stats');

Route::get('/judet/observers/quizes', 'Admin\JudetController@quizesAction')->name('judet.observers.quizes');
Route::get('/national/observers/quizes', 'Admin\NationalController@quizesAction')->name('national.observers.quizes');
Route::get('/superadmin/observers/quizes', 'Admin\SuperAdminController@quizesAction')->name('superadmin.observers.quizes');

Route::get('/observers/import', function() {
	/*
	ObserversImport::undoImport();
	die;
	*/

	print_r(ObserversImport::importCreate('/home/dev4a/public_html/vot/storage/observers.csv'));
	die;

	/*
	//$oi = new ObserversImport('/home/dev4a/public_html/vot/storage/observers-test.csv');
	$oi = new ObserversImport('/home/dev4a/public_html/vot/storage/observers.csv');
	//cu observatorii am ramas la 5
	echo '<pre>';
	$observers = $oi->getArray();
	$observers = $oi->getMainObservers($observers);
	$oi->importJudete($observers);
	$sections = $oi->getSections($observers);
	print_r($oi->importSections($sections, true));//
	print_r($oi->importObservers($observers));
	*/
	//echo '</pre>';
	return 'xxx';
});
















?>
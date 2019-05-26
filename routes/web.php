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
use App\Model\Message;
use App\Model\SMS;
use App\Model\SMSTest;
use Illuminate\Support\Facades\DB;


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

/*
//mai trebuie sa verificam si detaliile sectiei;todo: schimba datele unei sectii;sa vedem daca o gaseste
todo: adauga o sectie;
todo: modifca o sectie;
todo:adauga un judet?
sa verificam observatorii acum;
*/
function checkSections($judetName, $sectionNr, $sectionName) {
	$judet = DB::table("judete")->where('name', $judetName)->first();
	if (empty($judet)) {
		echo "judet negasit: $judetName<br/>";
		return;
	}

	$section = DB::table("sections")->where('judet_id', $judet->id)->where('nr', $sectionNr)->first();
	if (empty($judet) || empty($section)) {
		echo $judetName, ' ', $sectionNr, ' negasit<br/>';
		//die;
		return;
	}

	if ($section->name != $sectionName) {
		echo 'nume eronat: ', $judetName, ' ', $sectionNr, '<br/>'; 
	}
}

function checkObservers($judetName, $sectionNr, $observer) {
	if ($observer['type'] != '0') {
		return;
	}

	$judet = DB::table("judete")->where('name', $judetName)->first();
	$section = DB::table("sections")->where('judet_id', $judet->id)->where('nr', $sectionNr)->first();
	$observerDB = DB::table("observers")->where('section_id', $section->id)->first();
	if (empty($observerDB)) {
		echo 'negasit:<br/>';
		print_r($observer);
		echo '--------------------<br/>';
		return;
	}
	if ($observerDB->family_name != $observer['nume'] || $observerDB->given_name != $observer['prenume'] || $observerDB->phone != $observer['telefon']) {
		echo 'eroare:<br/>';
		print_r($observer);
		echo '--------------------<br/>';
		return;
	}
}

function checkImport($file) {
	$f = fopen($file, 'r');
	$rows = [];
	fgetcsv($f, 10000, ",");
	$i = 0;
	while ($row = fgetcsv($f, 10000, ",")) {
		//print_r($row);echo '<br/>';
		$judetName = $row[0];
		$sectionNr = $row[1];
		$sectionName = $row[7];
		//checkSections($judetName, $sectionNr, $sectionName);
		
		$nume = $row[2];
		$prenume = $row[3];
		$cnp = $row[4];
		$telefon = $row[6];
		$type = $row[9];
		checkObservers($judetName, $sectionNr, ['nume' => $nume, 'prenume' => $prenume, 'telefon' => $telefon, 'type' => $type]);
		
		echo $i, '<br/>';
		$i++;
		//echo 'bla<br/>';
	}
}


Route::get("/xxyy", function() {
	//checkImport("/home/dev4a/public_html/vot/storage/delegati_finali.csv");
	//die;
	
	
	//print_r(Section::getReferendumVotesCountForJudet(3));
	//print_r(Section::referendumSectionsCountForJudet(3));
	

	//ObserversImport::importCreate("/home/dev4a/public_html/vot/storage/delegati_finali.csv");
	//die;
	
	//checkSections('Alba', '1');

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

	//ObserversImport::undoImport();

	/*
	$observersCompletedQuizCount = Observer::completedQuizCount(['judet_id' => 2]);
	echo $observersCompletedQuizCount, '<br/>';

	foreach ($observersCompletedQuiz->get() as $o) {
		echo $o->id, ' ';
	}
	*/

	
	//print_r(Admin::addJudet(['judet_id' => 1, 'username' => 'ph2', 'password' => '1234', 'full_name' => 'aa bb'], DT::now()));
	//print_r(Admin::addJudet(['judet_id' => 1000, 'username' => 'national1', 'password' => '1234', 'full_name' => 'aa bb'], DT::now()));

	//echo '<pre>';
	//print_r(Message::createForNationalAction(['admin_id' => 1, 'content' => 'xoxo3'], DT::now()));
	//echo '</pre>';

	/*
	echo '<pre>';
	print_r(Section::judetElectionCount(1));
	*/

	//$sms = SMS::createFromEnv();
	//$idFromServer = $sms->sendMessageTo('hi', '40768340418');
	//$idFromServer = $sms->sendMessageTo('hi', 'aassaa');

	//print_r($sms->tryToConfirmSending($idFromServer, time(), 1));
	//print_r(json_decode($sms->getMessageStatusById('1671156')));

	//var_dump(file_exists('/home/dev4a/public_html/vot/storage/observers2.csv'));
	/*
	ObserversImport::undoImport();
	print_r(ObserversImport::importCreate('/home/dev4a/public_html/vot/storage/observers2.csv'));
	die;
	*/

	
	/*
	$rows = SMSTest::getObserversFromCSV('/home/dev4a/public_html/vot/storage/test-sms.csv', true);
	$result = SMSTest::getObservers($rows);
	foreach($result['observers'] as $o) {
		echo $o->id, ' ';
	}

	foreach ($result['notFound'] as $nf) {
		print_r($nf);
	}
	die;
	*/

	//SMSTest::sendSMSWithPin($result['observers']);
	//print_r(SMSTest::checkSentMessages());
	
	/*
	$sms = SMS::createFromEnv();
	print_r($sms->getMessageStatusById('1840273'));
	*/

	/*
	$sms = SMS::createFromEnv();
	echo $sms->sendMessageTo('Acesta este un sms de test, din partea USR-PLUS', '0034633184305');
	//SMSTest::sendToObserversFromCSV('/home/dev4a/public_html/vot/storage/test-sms.csv', SMS::createFromEnv());
	die;
	*/

	//echo Observer::countLoginsInJudet(1);

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
Route::get('/observer/send_sms', 'ObserverController@sendSMSAction')->name('observer.sms.send');

Route::get('/observer/quiz', 'ObserverController@quizAction')->name('observer.quiz');

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

Route::get('/superadmin/admin/add', 'Admin\SuperAdminController@addAdminShowAction')->name('superadmin.admins.judet.add.show');
Route::post('/superadmin/admin/add', 'Admin\SuperAdminController@addAdminAction')->name('superadmin.admins.judet.add');

Route::get('/superadmin/admin/import', 'Admin\SuperAdminController@importAdminsAction')->name('superadmin.admins.import');

Route::get('/national/election/count', 'Admin\NationalController@countNationalElectionAction')->name('national.election.count');
Route::get('/national/election/judet/count', 'Admin\NationalController@countJudetElectionAction')->name('national.election.judet.count');
Route::post('/national/election/judet/count/export', 'Admin\NationalController@exportCountJudetElectionAction')->name('national.election.judet.count.export');

Route::post("/observer/save_ref", 'ObserverController@saveRef')->name('observer.ref.save');

Route::get("/judet/referendum/update/{sectionId}", 'Admin\JudetController@showReferendumUpdateAction')->name('judet.referendum.update.show');
Route::post("/judet/referendum/update/{sectionId}", 'Admin\JudetController@referendumUpdateAction')->name('judet.referendum.update');
Route::get("/national/referendum/update/{sectionId}", 'Admin\NationalController@showReferendumUpdateAction')->name('national.referendum.update.show');
Route::post("/national/referendum/update/{sectionId}", 'Admin\NationalController@referendumUpdateAction')->name('national.referendum.update');

Route::get("/superadmin/referendum/update/{sectionId}", 'Admin\SuperAdminController@showReferendumUpdateAction')->name('superadmin.referendum.update.show');
Route::post("/superadmin/referendum/update/{sectionId}", 'Admin\SuperAdminController@referendumUpdateAction')->name('superadmin.referendum.update');

Route::post("/national/sections/export_by_login_status", 'Admin\NationalController@exportSectionsByloginStatusAction')->name('national.sections.export_by_login_status');
Route::post("/judet/sections/export_by_login_status", 'Admin\JudetController@exportSectionsByloginStatusAction')->name('judet.sections.export_by_login_status');
Route::post("/superadmin/sections/export_by_login_status", 'Admin\SuperAdminController@exportSectionsByloginStatusAction')->name('superadmin.sections.export_by_login_status');

Route::get("/observer/votes/", 'ObserverController@votesAction')->name('observer.votes');

Route::get("/national/section", 'Admin\NationalController@sectionAction')->name('national.section');
Route::get("/judet/section", 'Admin\JudetController@sectionAction')->name('judet.section');

Route::get("/national/section/add", 'Admin\NationalController@sectionAddShowAction')->name('national.section.add.show');
Route::post("/national/section/add", 'Admin\NationalController@sectionAddAction')->name('national.section.add');

/*
Route::get('/judet/message', 'Admin\JudetController@showMessageAction')->name('judet.message');
Route::post('/judet/message/upsert', 'Admin\JudetController@upsertMessageAction')->name('judet.message.upsert');
*/

Route::get('national/account/create/national', 'Admin\NationalController@createNationalAccountShowAction')->name('national.account.create.national.show');
Route::post('national/account/create/national', 'Admin\NationalController@createNationalAccountAction')->name('national.account.create.national');

Route::get('national/account/update/national', 'Admin\NationalController@updateNationalAccountShowAction')->name('national.account.update.national.show');
Route::post('national/account/update/national', 'Admin\NationalController@updateNationalAccountAction')->name('national.account.update.national');


Route::get('national/account/create/judet', 'Admin\NationalController@createJudetAccountShowAction')->name('national.account.create.judet.show');
Route::post('national/account/create/judet', 'Admin\NationalController@createJudetAccountAction')->name('national.account.create.judet');
Route::get('national/account/update/judet', 'Admin\NationalController@updateJudetAccountShowAction')->name('national.account.update.judet.show');
Route::post('national/account/update/judet', 'Admin\NationalController@updateJudetAccountAction')->name('national.account.update.judet');


Route::get('judet/account/create/judet', 'Admin\JudetController@createJudetAccountShowAction')->name('judet.account.create.judet.show');
Route::post('judet/account/create/judet', 'Admin\JudetController@createJudetAccountAction')->name('judet.account.create.judet');
Route::get('judet/accounts/', 'Admin\JudetController@accountsShowAction')->name('judet.accounts.show');
Route::get('judet/account/', 'Admin\JudetController@updateJudetAccountShowAction')->name('judet.account.update.show');
Route::post('judet/account/', 'Admin\JudetController@updateJudetAccountAction')->name('judet.account.update');


Route::get('national/quiz/export', 'Admin\NationalController@quizExportAction')->name('national.quiz.export');


/*
Route::get('judet/account/update/judet', 'Admin\JudetController@updateJudetAccountShowAction')->name('judet.account.update.judet.show');
Route::post('judet/account/update/judet', 'Admin\JudetController@updateJudetAccountAction')->name('judet.account.update.judet');
*/


Route::get('national/admins/national', 'Admin\NationalController@adminsNationalAction')->name('national.admins.show');

Route::get('national/admins/national/delete', 'Admin\NationalController@adminsNationalDeleteActionAction')->name('national.admins.national.delete');

Route::get('national/admins/judet', 'Admin\NationalController@adminsJudetAction')->name('judet.admins.show');

Route::get('national/admins/judet/delete', 'Admin\NationalController@adminsJudetDeleteActionAction')->name('national.admins.judet.delete');

Route::get('logout', 'Admin\AdminController@logoutAction')->name('admin.logout');

Route::get('judet/election/count', 'Admin\JudetController@electionCountAction')->name('judet.election.count');

Route::get('judet/quiz/export', 'Admin\JudetController@quizExportAction')->name('judet.quiz.export');

Route::get('national/referendum/totals', 'Admin\NationalController@referendumTotalsAction')->name('national.referendum.totals');

Route::get('judet/referendum/totals', 'Admin\JudetController@referendumTotalsAction')->name('judet.referendum.totals');

Route::get('national/sections/export', 'Admin\NationalController@exportSectionsAction')->name('national.sections.export');

Route::get('national/sections/errors', 'Admin\NationalController@errorsAction')->name('national.sections.errors');


Route::get('/observers/import', function() {
	/*
	ObserversImport::undoImport();
	die;
	*/

	/*
	print_r(ObserversImport::importCreate('/home/dev4a/public_html/vot/storage/observers.csv'));
	die;
	*/

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
<?php
use App\Model\Admin\Admin;
use App\Model\Judet;
use App\Model\Observer;
use App\Model\Section;
use App\Model\Session;
use App\Model\SessionToken;
use App\Model\Pin;
use App\Functions\DT;

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

	return view('index');
});

Route::post("/observer/login", 'ObserverController@loginAction')->name('observer.login');
Route::get('/admin/login', 'Admin\AdminController@loginActionShow')->name('admin.login.show');
Route::post('/admin/login', 'Admin\AdminController@loginAction')->name('admin.login');


Route::get('/judet/observers', 'Admin\JudetController@observersActionShow')->name('judet.observers.show');
Route::get('/national/observers', 'Admin\NationalController@observersActionShow')->name('national.observers.show');
Route::get('/superadmin/observers', 'Admin\SuperAdminController@observersActionShow')->name('superadmin.observers.show');

?>
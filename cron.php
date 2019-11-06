<?php
require '/var/www/app.danbarna.ro/html/dashboard/vendor/autoload.php';
$app = require_once '/var/www/app.danbarna.ro/html/dashboard/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

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
foreach (Job::getJobsToRun() as $job) {
	$job->status = Job::STATUS_RUNNING;
	$job->save();
	$observers = $job->getObservers();
	foreach ($observers as $o) {
		echo $o->id, "\n";//trimite sms
		//mail('')
		//mail($o->phone, 'sss', $job->message);
	}

	$job->status = Job::STATUS_DONE;
	$job->save();
}
*/


//SMSTest::sendToObserversFromCSV('/var/www/app.danbarna.ro/html/dashboard/storage/test-sms.csv', SMS::createFromEnv());
/*
$rows = SMSTest::getObserversFromCSV('/var/www/app.danbarna.ro/html/dashboard/storage/test-joined.csv', false);
$result = SMSTest::getObservers($rows);
SMSTest::generatePins($result['observers']);
//print_r($result['observers']);
print_r($result['notFound']);
die;
*/

/*
$observersNoPin = Observer::where('pin', null)->get();
$pin = new Pin();

foreach ($observersNoPin as $observer) {
	$observer->pin = $pin->generate();
	$observer->save();
}

$fp = fopen('file.csv', 'w');
foreach ($observersNoPin as $observer) {
	fputcsv($fp, [$observer->phone, $observer->pin]);
}
fclose($fp);
*/

/*
$handle = fopen("file.csv", "r");
while (($row = fgetcsv($handle, 10000, ",")) != false) {
	$observer = Observer::where('phone', $row[0])->first();
	if ($observer->pin != $row[1]) {
		echo $observer->id, "\n";
	}
}
*/

$observers = Observer::all();
foreach ($observers as $o) {
	if (Observer::where('phone', $o->phone)->count() > 1) {
		echo $o->id, " ", $o->phone, "\n";
	}
}

?>

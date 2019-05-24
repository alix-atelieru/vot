<?php
require '/home/dev4a/public_html/vot/vendor/autoload.php';
$app = require_once '/home/dev4a/public_html/vot/bootstrap/app.php';
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


//SMSTest::sendToObserversFromCSV('/home/dev4a/public_html/vot/storage/test-sms.csv', SMS::createFromEnv());

$rows = SMSTest::getObserversFromCSV('/home/dev4a/public_html/vot/storage/test-joined.csv', false);

$result = SMSTest::getObservers($rows);
SMSTest::generatePins($result['observers']);
//print_r($result['observers']);
print_r($result['notFound']);
die;
?>
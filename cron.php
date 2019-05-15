<?php
require '/home/dev4a/public_html/vot/vendor/autoload.php';
$app = require_once '/home/dev4a/public_html/vot/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Model\Job;

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

?>
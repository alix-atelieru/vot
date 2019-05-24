<?php
namespace App\Model;
use App\Functions\DT;
use App\Model\SmsRequest;

class SMSTest {
	public static function getObserversFromCSV($csvPath, $skipDiaspora) {
		$f = fopen($csvPath, 'r');
		$rows = [];
		fgetcsv($f, 100000, ",");
		while (($row = fgetcsv($f, 100000, ",")) !== false) {
			/*
			if ($row[0] == 'Diaspora' && !$skipDiaspora) {
				continue;
			}
			*/
			$rows[] = ['judetName' => $row[0], 'sectionNr' => $row[1]];
		}
		return $rows;
	}

	public static function getObserverByJudetAndSection($judetName, $sectionNr) {
		$judet = Judet::where('name', $judetName)->first();
		if (empty($judet)) {
			return false;
		}
		$section = Section::where('judet_id', $judet->id)->where('nr', $sectionNr)->first();
		if (empty($section)) {
			return false;
		}
		$observer = Observer::where('section_id', $section->id)->first();
		return $observer;
	}

	public static function getObservers($rows) {
		$observers = [];
		$notFound = [];
		foreach ($rows as $row) {
			$observer = self::getObserverByJudetAndSection($row['judetName'], $row['sectionNr']);
			//if ($observer->)
			if (!empty($observer)) {
				$observers[] = $observer;
			} else {
				$notFound[] = $row;
			}
		}

		return ['observers' => $observers, 'notFound' => $notFound];
	}

	public static function generatePins($observers) {
		$pin = new Pin();
		$i = 0;
		foreach ($observers as $observer) {
			/*
			if ($observer->pin_sent == 1) {
				continue;
			}
			*/
			echo $i, "\n";
			$i++;
			$observer->pin = $pin->generate();
			var_dump($observer->save());
		}
	}

	public static function sendSMSWithPin($observers) {
		$sms = SMS::createFromEnv();
		self::generatePins($observers);
		foreach ($observers as $observer) {
			if ($observer->pin_sent == 1) {
				continue;
			}
			
			echo $observer->id, "\n";
			$message = "Codul tau este:". $observer->pin;
			$idFromServer = $sms->sendMessageTo($message, $observer->phone);
			$request = new SmsRequest();
			$request->sent_at = DT::now();
			$request->id_from_server = $idFromServer;
			$request->sending_checked = 0;
			$request->message = $message;
			$request->phone = $observer->phone;
			$request->save();
			usleep(80000);//0.08 secunde
		}
	}

	//ia observatorul si marcheaza ca i s-a trimis pinul;
	public static function checkSentMessages($sms) {
		$uncheckedRequests = SmsRequest::getNotCheckedRequests();
		$errors = [];
		foreach ($uncheckedRequests as $request) {
			$response = $sms->getMessageStatusById($request->id_from_server);
			$checkResult = json_decode($response, 'ARRAY_A');
			if (empty($checkResult)) {
				$errors[] = ['type' => 'decoding_issue', 'response' => $response];
				continue;
			}

			$observer = Observer::where('phone', $request->phone)->first();
			if (empty($observer)) {
				$errors[] = ['type' => 'phone_not_found', 'phone' => $phone];
			}
			if ($checkResult['status'] == 'expediat') {
				$observer->pin_sent = 1;
				$observer->save();
			}

			$request->sending_checked = 1;
			$request->check_result = $checkResult['status'];
			$request->check_response = $response;
			$request->save();
			usleep(80000);//0.08 secunde
		}

		return $errors;
	}

	public static function sendToObserversFromCSV($csv, $sms) {
		$rows = SMSTest::getObserversFromCSV($csv, true);//sari peste diaspora;
		$result = SMSTest::getObservers($rows);
		$observers = $result['observers'];
		self::sendSMSWithPin($observers);
		echo "verificare trimitere SMS:\n";
		sleep(12);
		//cat dureaza sa se trimita
		self::checkSentMessages($sms);
	}
}
?>
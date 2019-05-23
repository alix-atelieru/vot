<?php
namespace App\Model;

class SMS {
	public static function createFromDict($config) {
		$self = new self();
		$self->endpoint = $config['endpoint'];
		$self->checkTimeout = $config['check_timeout'];
		$self->user = $config['user'];
		$self->apiKey = $config['api_key'];
		$self->sender = $config['sender'];
		return $self;
	}

	public static function createFromEnv() {
		return self::createFromDict(['endpoint' => env('SMS_ENDPOINT'),
									 'user' => env('SMS_API_USER'),
									 'check_timeout' => env('SMS_CHECK_TIMEOUT'),
									 'api_key' => env('SMS_API_KEY'),
									 'sender' => env('SMS_SENDER')
									]);
	}

	public function sendMessageTo($message, $phone) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->endpoint);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		$query = http_build_query(['api_user' => $this->user, 
								   'api_key' => $this->apiKey, 
								   'comanda' => 'trimite_sms',
								   'sender' => $this->sender,
								   'nr' => $phone,
								   'mesaj' => $message
								]);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$idFromServer = curl_exec($ch);
		return $idFromServer;
	}

	public function getMessageStatusById($idFromServer) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->endpoint);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		$query = http_build_query(['api_user' => $this->user, 
								   'api_key' => $this->apiKey, 
								   'comanda' => 'verifica_status_sms',
								   'id_mesaj' => $idFromServer
								]);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($ch);
	}

	/*
	avem timeout;da sleep 1 secunda;
	daca nu e expediat, il punem cu statusul eroare si adaugam ultimul raspuns
	*/
	public function tryToConfirmSending($idFromServer, $startTimestamp, $cooldown) {
		while(1) {
			$response = $this->getMessageStatusById($idFromServer);
			$responseDict = json_decode($response, 'ARRAY_A');
			if (!empty($responseDict['status']) && $responseDict['status'] == 'expediat') {
				$responseDict['checkExpired'] = false;
				return $responseDict;
			}
			$timePassed = time()-$startTimestamp;
			if ($timePassed > $this->checkTimeout) {
				//returneaza ultimul raspuns
				$responseDict['checkExpired'] = true;
				return $responseDict;
			}
			sleep($cooldown);
		}
	}
}
?>
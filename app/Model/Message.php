<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\Judet;

class Message extends Model {

	/*
	daca exista in judet->tre inlocuit;
	daca nu exista->tre creat
	admin_id ce reprezinta?
	*/
	public static function createForJudetAction($requestDict, $now) {
		if (empty($requestDict['judet_id']) || empty($requestDict['admin_id']) || empty($requestDict['content'])) {
			return ['ok' => false, 'errorLabel' => 'Camp lipsa'];
		
		}
		
		$judet = Judet::find($requestDict['judet_id']);
		if (empty($judet)) {
			return ['ok' => false, 'errorLabel' => 'Judetul nu exista'];
		}

		//$message = Message::where('judet_id', $requestDict['judet_id'])->first();
		$message = Message::findForJudet($requestDict['judet_id']);
		if (!empty($message)) {
			$message->content = $requestDict['content'];
			$message->updated_by = $requestDict['admin_id'];
			$message->updated_at = $now;
			$message->save();
			return ['ok' => true];
		}

		$message = new Message();
		$message->content = $requestDict['content'];
		$message->updated_by = $requestDict['admin_id'];
		$message->created_by = $requestDict['admin_id'];
		$message->type = Admin::TYPE_JUDET;
		$message->judet_id = $requestDict['judet_id'];
		$message->updated_at = $now;
		$message->created_at = $now;
		$message->save();
		return ['ok' => true];	
	}

	public static function createForNationalAction($requestDict, $now) {
		if (empty($requestDict['admin_id']) || empty($requestDict['content'])) {
			return ['ok' => false, 'errorLabel' => 'Camp lipsa'];
		}

		$nationalMessage = Message::findForNational();
		if (!empty($nationalMessage)) {
			$nationalMessage->content = $requestDict['content'];
			$nationalMessage->updated_by = $requestDict['admin_id'];
			$nationalMessage->updated_at = $now;
			$nationalMessage->save();
			return ['ok' => true];
		}

		$nationalMessage = new Message();
		$nationalMessage->content = $requestDict['content'];
		$nationalMessage->updated_by = $requestDict['admin_id'];
		$nationalMessage->created_by = $requestDict['admin_id'];
		$nationalMessage->type = Admin::TYPE_NATIONAL;
		$nationalMessage->judet_id = null;
		$nationalMessage->updated_at = $now;
		$nationalMessage->created_at = $now;
		$nationalMessage->save();

		return ['ok' => true];
	}

	public static function findForNational() {
		return Message::where('type', Admin::TYPE_NATIONAL)->first();
	}

	public static function findForJudet($judetId) {
		return self::where('judet_id', $judetId)->first();
	}

}

?>
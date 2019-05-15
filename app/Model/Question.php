<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/*
questions: id, content, position=0,1,2
questions_answers: id, question_id, observer_id, answer
quiz_last_updated_datetime
*/
class Question extends Model {
	public $timestamps = false;
	//public $table = "judete";
	
	public static function sortedAll() {
		return DB::select("select * from questions order by position asc");
	}
	
}

?>
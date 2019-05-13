<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Judet extends Model {
	public $timestamps = false;
	public $table = "judete";

	public function admins() {
		return $this->hasMany('App\Model\Admnin');
	}

	public function sections() {
		return $this->hasMany('App\Model\Section');
	}
}

?>
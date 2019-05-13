<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {
	public $timestamps = false;
	
	public function observers() {
		return $this->hasMany('App\Model\Observer');
	}
}

?>
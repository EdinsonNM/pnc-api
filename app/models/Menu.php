<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
	protected $table = 'menu';

	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array();

	public function children(){
		return $this->hasMany('App\models\Menu','idpadre','id');
	}

}

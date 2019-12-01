<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;
class Acceso extends Model {
	protected $table = 'acceso';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array();

	public function menu(){
		return $this->belongsTo('Menu', 'menu_id', 'id');
	}

}

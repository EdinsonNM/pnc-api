<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EncuestaPreguntaOpcion extends Model {
	protected $table='encuestapreguntaopcion';
	// Add your validation rules here
	public static $rules =array();

	// Don't forget to fill this array
	protected $fillable = array('pregunta_id','opcion','peso');

}

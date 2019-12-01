<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EvaluadorDisponibilidad extends Model {
	protected $table = 'evaluadordisponibilidad';
	// Add your validation rules here
	public static $rules =array();

	// Don't forget to fill this array
	protected $fillable = array('dia_id','manana','tarde','noche','evaluador_id');

}

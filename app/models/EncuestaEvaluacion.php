<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EncuestaEvaluacion extends Model {
	protected $table='encuestaevaluacion';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('grupoevaluacion_id','usuario_evaluador_id','encuesta_id','abierta');

	public function respuestas(){
		return $this->hasMany('App\models\EncuestaEvaluacionRespuesta','encuestaevaluacion_id','id');
	}
	public function encuesta(){
		return $this->belongsTo('App\models\Encuesta','encuesta_id');
	}

}

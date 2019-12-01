<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EncuestaEvaluacionRespuesta extends Model {
	protected $table='encuestaevaluacionrespuesta';

	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('encuestaevaluacion_id','pregunta_id','opcion_id','evaluador_id','peso','comentario');
	public function evaluador(){
		return $this->belongsTo('App\models\Evaluador','evaluador_id');
	}
}

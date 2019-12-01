<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class GrupoEvaluacion extends Model {
	protected $table = 'grupoevaluacion';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','concurso_id','nombre','descripcion');

	public function concurso(){
		return $this->belongsTo('App\models\Concurso', 'concurso_id', 'id');
	}
	public function evaluadores(){
		return $this->hasMany('App\models\GrupoEvaluacionEvaluador', 'grupoevaluacion_id', 'id');
	}
	public function encuestaevaluaciones(){
		return $this->hasMany('App\models\EncuestaEvaluacion', 'grupoevaluacion_id', 'id');
	}

}

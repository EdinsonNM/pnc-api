<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Concurso extends Model {
	protected $table = 'concurso';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','nombreconcurso','fechainicio','fechafin','estado','presentacion','anio','estado_empresa','estado_proyecto','termino_aceptacion','termino_aceptacion_evaluador','incrementopuntaje','evalua_criterio','informe_retro','calificacion','tipoconcurso_id','condiciones_evaluador','onlyTest');

	public function tipo_concurso(){
		return $this->belongsTo('App\models\Catalogo', 'tipoconcurso_id', 'id');
	}

	public function inscripciones(){
		return $this->hasMany('App\models\Inscripcion', 'concurso_id', 'id');
	}

	public function inscripcionesevaluador(){
		return $this->hasMany('App\models\InscripcionEvaluador', 'concurso_id', 'id');
	}

	public function etapas(){
		return $this->hasMany('App\models\EtapaConcurso','concurso_id','id');
	}

}

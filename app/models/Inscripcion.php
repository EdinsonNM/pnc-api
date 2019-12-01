<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model {
	protected $table = 'inscripcion';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','nombreproyecto','nombrecorto','integrantes','objetivoproyecto','fechainiciopy','fechafinpy','informepostulacionc','informepostulacionsic','terminoaceptacion','concurso_id','nombreequipo','postulante_id','logros','impacto_economico');

	public function postulante(){
		return $this->belongsTo('App\models\Postulante', 'postulante_id', 'id');
	}

	public function concurso(){
		return $this->belongsTo('App\models\Concurso', 'concurso_id', 'id');
	}

	public function evaluaciones(){
		return $this->hasMany('App\models\Evaluacion', 'inscripcion_id', 'id');
	}

	public function impactosproyecto(){
		return $this->hasMany('App\models\Impactoproyecto', 'inscripcion_id', 'id');
	}


}

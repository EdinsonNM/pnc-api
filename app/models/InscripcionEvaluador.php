<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class InscripcionEvaluador extends Model {
	protected $table = 'inscripcionevaluador';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','concurso_id','evaluador_id');

	public function evaluador(){
		return $this->belongsTo('App\models\Evaluador','evaluador_id');
	}

	public function concurso(){
		return $this->belongsTo('App\models\Concurso','concurso_id','id');
	}

}

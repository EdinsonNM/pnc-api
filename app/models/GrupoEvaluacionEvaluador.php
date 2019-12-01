<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class GrupoEvaluacionEvaluador extends Model {
	protected $table = 'grupoevaluacionevaluador';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('grupoevaluacion_id','inscripcion_id','id');

	public function inscripcion(){
		return $this->belongsTo('App\models\InscripcionEvaluador', 'inscripcion_id', 'id');
	}

	public function grupoevaluacion(){
		return $this->belongsTo('App\models\GrupoEvaluacion', 'grupoevaluacion_id', 'id');
	}

	public function permisos(){
		return $this->hasMany('App\models\GrupoEvaluacionEvaluadorPermiso', 'grupoevaluacionevaluador_id', 'id');
	}

	public function evaluaciones(){
		return $this->hasMany('App\models\GrupoEvaluacionPostulante','grupoevaluacion_id','grupoevaluacion_id')
		->with(array('inscripcion'=>function($q){
			return $q->with('evaluaciones');
			}));

		//->where('evaluador_id',$this->inscripcion()->evaluador_id)->orWhere('evaluador_id','0');
	}

}

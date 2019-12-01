<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class GrupoEvaluacionPostulante extends Model {
	protected $table = 'grupoevaluacionpostulante';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('grupoevaluacion_id','inscripcion_id','codigo','visita','fechavisita','ganador','tipopremio_id');

	public function inscripcion(){
		return $this->belongsTo('App\models\Inscripcion', 'inscripcion_id', 'id');
	}

	public function grupoevaluacion(){
		return $this->belongsTo('App\models\GrupoEvaluacion', 'grupoevaluacion_id', 'id');
	}

	public function tipopremio(){
		return $this->belongsTo('App\models\Catalogo','tipopremio_id','codigo')
		->where('codcatalogo','=','TIPOPREMIO');
	}

}

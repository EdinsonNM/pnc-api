<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model {
	protected $table = 'respuesta';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','evaluacion_id','concursocriterio_id','respuesta','puntaje','aspectoclave_id','estado','areaanalisis_id','pregunta_id','evaluador_created','evaluador_updated');

	public  function areaanalisis(){
		return $this->belongsTo('App\models\ConcursoCriterio','areaanalisis_id','id');
	}

	public  function pregunta(){
		return $this->belongsTo('App\models\ConcursoCriterio','pregunta_id','id');
	}

	public function creado_por()
	{
		return $this->belongsTo('App\models\Evaluador','evaluador_created','id');
	}

	public function actualizado_por()
	{
		return $this->belongsTo('App\models\Evaluador','evaluador_updated','id');
	}

}

<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class AspectoClave extends Model {
	protected $table = 'aspectoclave';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','concursocriterio_id','evaluacion_id','descripcion','estado','evaluador_created','evaluador_updated');

	public function criterio(){
		return $this->belongsTo('App\models\ConcursoCriterio','concursocriterio_id','id');
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

<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ConcursoCriterio extends Model {
	protected $table = 'concursocriterio';

	// Add your validation rules here
	public static $rules =array();

	// Don't forget to fill this array
	protected $fillable = array('id','concurso_id','tipocriterio_id','tipoarbol_id','idpadre','codigo','descripcion','puntaje','glosa','comentario','detalle','proposito','nota');

	public function children(){
		return $this->hasMany('App\models\ConcursoCriterio','idpadre','id');
	}
	public function TipoArbol(){
		return $this->belongsTo('App\models\Catalogo','tipoarbol_id','id');
	}

	public function puntajes(){
		return $this->hasMany('App\models\Puntaje','concursocriterio_id','id');
	}

	public function visitas(){
		return $this->hasMany('App\models\CriterioVisita','concursocriterio_id','id');
	}
	public function respuestas(){
		return $this->hasMany('App\models\Respuesta','concursocriterio_id','id');
	}

	public function criterio_aspectosclaves(){
		return $this->hasMany('App\models\CriterioAspectoClave','concursocriterio_id','id');
	}

	public function criterio_aprobaciones()
	{
		return $this->hasMany('App\models\CriterioAprobacion','concursocriterio_id','id');
	}

	public function aspectosclaves(){
		return $this->hasMany('App\models\AspectoClave','concursocriterio_id','id');
	}
}

<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Impactoproyecto extends Model {
	protected $table="impactoproyectos";
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('inscripcion_id','impactoproyecto_id');

	public function inscripcion()
	{
		return $this->belongsTo('App\models\Inscripcion','inscripcion_id','id');
	}

	public function tipoimpactoproyecto(){
		return $this->belongsTo('App\models\Catalogo','impactoproyecto_id','codigo')
		->where('codcatalogo','=','NATURALEZAPROYECTO');
	}

}

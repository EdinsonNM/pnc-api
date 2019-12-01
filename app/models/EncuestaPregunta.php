<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EncuestaPregunta extends Model {
	protected $table='encuestapregunta';

	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('grupopregunta_id','titulo','pregunta','orden','encuesta_id','tipoetapa_id','tiporespuesta','check_validacion_evaluador');

	public function encuesta(){
		return $this->belongsTo('App\models\Encuesta', 'encuesta_id', 'id');
	}

	public function children(){
		return $this->hasMany('App\models\EncuestaPregunta','grupopregunta_id','id');
	}

	public function opciones(){
		return $this->hasMany('App\models\EncuestaPreguntaOpcion','pregunta_id','id');
	}

	public function tipo_etapa(){
		return $this->belongsTo('App\models\Catalogo','tipoencuesta_id','codigo')
		->where('codcatalogo','=','TIPOETAPA');
	}

}

<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model {
	protected $table='encuesta';

	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('tipoencuesta_id','titulo','pregunta','inicio','fin','estado','concurso_id');

	public function tipo_encuesta(){
		return $this->belongsTo('App\models\Catalogo','tipoencuesta_id','codigo')
		->where('codcatalogo','=','TIPOENCUESTA');
	}

	public function concurso(){
		return $this->belongsTo('App\models\Concurso', 'concurso_id', 'id');
	}
}

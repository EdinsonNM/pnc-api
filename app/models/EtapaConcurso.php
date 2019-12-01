<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class EtapaConcurso extends Model {
	protected $table = 'etapaconcurso';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('fechaInicio','fechaFin','extendido','fechaExtension','etapa_id','concurso_id');

	public function etapa(){
		return $this->belongsTo('App\models\Etapa','etapa_id','id');
	}
}

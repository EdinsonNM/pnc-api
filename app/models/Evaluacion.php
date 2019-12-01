<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model {
	protected $table = 'evaluacion';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','evaluador_id','inscripcion_id','tipoetapa_id','fechacierre','abierta','importdata');

	public function evaluador(){
		return $this->belongsTo('App\models\Evaluador','evaluador_id');
	}

}

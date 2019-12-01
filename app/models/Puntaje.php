<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Puntaje extends Model {
	protected $table = 'puntaje';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','evaluacion_id','concursocriterio_id','valor');

	public function evaluacion()
	{
		return $this->belongsTo('App\models\Evaluacion','evaluacion_id');
	}

}

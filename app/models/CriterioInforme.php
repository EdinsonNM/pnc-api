<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class CriterioInforme extends Model {
	protected $table = 'criterioinforme';
		// Add your validation rules here
		public static $rules = array();

		// Don't forget to fill this array
		protected $fillable = array('id','concursocriterio_id','evaluacion_id','informe','tipo');

		public function evaluacion()
		{
			return $this->belongsTo('App\models\Evaluacion','evaluacion_id');
		}

}

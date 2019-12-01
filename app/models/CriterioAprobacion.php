<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class CriterioAprobacion extends Model {
protected $table = 'criterioaprobacion';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','concursocriterio_id','evaluacion_id','evaluador_id','aprobado');

}

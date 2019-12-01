<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class GrupoEvaluacionEvaluadorPermiso extends Model {
	protected $table="grupoevaluacionevaluador_permiso";

	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('grupoevaluacionevaluador_id','grupoevaluacionpostulante_id','tipoetapa_id','fechaextension');

}

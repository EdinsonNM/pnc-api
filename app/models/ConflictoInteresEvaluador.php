<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ConflictoInteresEvaluador extends Model {
	protected $table = 'conflictointeresevaluador';

	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','tipovinculointeres_id','razonsocial','ruc','fecini','fecfin','detalle','evaluador_id','hastalafecha');

	public function TipoVinculo(){
		return $this->belongsTo('App\models\Catalogo','tipovinculointeres_id','id');
	}

}

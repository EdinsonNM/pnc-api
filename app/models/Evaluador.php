<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;
class Evaluador extends Model {
	protected $table = 'evaluador';
	// Add your validation rules here
	public static $rules = array(
		// 'title' => 'required'
	);

	// Don't forget to fill this array
	protected $fillable = array('id','usuario_id','catalogo_tsx_id','disponibleviaje','nombres','apellidos','numdoc','email1','email2','direccion','telefono','celular','profesion','especializacion','cargo','empresa','direccionempresa','distritoemp','telefonoemp','faxemp','emailemp');

	public function usuario(){
		return $this->belongsTo('App\models\User', 'usuario_id', 'id');
	}

	public function sexo(){
		return $this->belongsTo('App\models\Catalogo', 'catalogo_tsx_id', 'id');
	}

	public function disponibilidades(){
		return $this->hasMany('App\models\EvaluadorDisponibilidad','evaluador_id','id');
	}

	public function gruposevaluacion(){
		return $this->hasMany('App\models\GrupoEvaluacionEvaluador','evaluador_id','id');
	}

	public function conflictosinteres(){
		return $this->hasMany('App\models\ConflictoInteresEvaluador','evaluador_id','id');
	}

	public function inscripciones(){
		return $this->hasMany('App\models\InscripcionEvaluador','evaluador_id','id');
	}

}

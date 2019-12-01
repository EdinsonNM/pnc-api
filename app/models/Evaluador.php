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
		return $this->belongsTo('User', 'usuario_id', 'id');
	}

	public function sexo(){
		return $this->belongsTo('Catalogo', 'catalogo_tsx_id', 'id');
	}

	public function disponibilidades(){
		return $this->hasMany('EvaluadorDisponibilidad','evaluador_id','id');
	}

	public function gruposevaluacion(){
		return $this->hasMany('GrupoEvaluacionEvaluador','evaluador_id','id');
	}

	public function conflictosinteres(){
		return $this->hasMany('ConflictoInteresEvaluador','evaluador_id','id');
	}

	public function inscripciones(){
		return $this->hasMany('InscripcionEvaluador','evaluador_id','id');
	}

}

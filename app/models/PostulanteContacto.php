<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class PostulanteContacto extends Model {
	protected $table = 'postulantecontacto';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('id','catalogo_tc_id','postulante_id','nombre','cargo','telefono','fax','email');

	public function TipoContacto(){
		return $this->belongsTo('App\models\Catalogo', 'catalogo_tc_id', 'id');
	}

}

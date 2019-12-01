<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model {
	protected $table = 'postulante';
	// Add your validation rules here
	public static $rules = array(
		// 'title' => 'required'
	);

	// Don't forget to fill this array
	protected $fillable =array('id','web','telefono','ruc','razonsocial','fax','direccion');

	public function usuario(){
		return $this->belongsTo('User', 'usuario_id', 'id');
	}

	public function categorias()
    {
        return $this->hasMany('PostulanteCategoria','postulante_id','id');
    }

    public function contactos()
    {
        return $this->hasMany('PostulanteContacto','postulante_id','id');
    }

}

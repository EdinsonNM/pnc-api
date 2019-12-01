<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {


	// Don't forget to fill this array
	protected $fillable = array();

	public function accesos(){
        return $this->hasMany('App\models\Acceso', 'perfil_id','id');
    }

}

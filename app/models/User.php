<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class User extends Model {
	protected $table = 'users';
	protected $fillable = array('email','username','nrodocumento','imagen','validaregistro','tipodocumento_id', 'password', 'group_id', 'imagen', 'activation_code','first_name', 'last_name');

	protected $hidden = array('password', 'remember_token');
	public static $rules = array(
		'nrodocumento'=>'required|unique:users'
	);

	public static $update_rules = array();

	public function perfil(){
		return $this->belongsTo('App\models\Group', 'group_id', 'id');
	}

	public function postulante()
    {
        return $this->hasOne('App\models\Postulante','usuario_id','id');
    }

    public function evaluador()
    {
        return $this->hasOne('App\models\Evaluador','usuario_id','id');
    }
    public static function createEvaluador(){

    }

}

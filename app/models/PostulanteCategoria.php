<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class PostulanteCategoria extends Model {
	protected $table = 'postulantecategoria';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable = array('postulante_id','catalogo_id','tipo');

	public function postulante(){
		return $this->belongsTo('App\models\Postulante', 'postulante_id', 'id');
	}

    public function catalogo()
    {
        return $this->belongsTo('App\models\Catalogo', 'catalogo_id', 'id');
    }

}

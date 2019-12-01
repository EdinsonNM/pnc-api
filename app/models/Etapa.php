<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class Etapa extends Model {
	protected $table = 'etapa';
	// Add your validation rules here
	public static $rules =array();

	// Don't forget to fill this array
	protected $fillable = array('tipoconcurso_id','tipoetapa_id','nombre','orden','estado');

	public function TipoConcurso(){
		return $this->belongsTo('App\models\Catalogo','tipoconcurso_id','id');
	}
	public function TipoEtapa(){
		return $this->belongsTo('App\models\Catalogo','tipoetapa_id','id');
	}

}

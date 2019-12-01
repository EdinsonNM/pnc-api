<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class CriterioAspectoClave extends Model {
	protected $table = 'criterioaspectoclave';
	// Add your validation rules here
	public static $rules = array();

	// Don't forget to fill this array
	protected $fillable =array('id','concursocriterio_id','aspectoclave_id','estado','evaluacion_id');

	public function aspectoclave(){
		return $this->belongsTo('App\models\AspectoClave','aspectoclave_id','id');
	}

}

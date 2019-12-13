<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Encuesta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class EncuestaEvaluacionRespuestasController extends Controller {

	public function store(Request $request)
	{

		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), EncuestaEvaluacionRespuesta::$rules);
		if (!$validator->fails())
		{
			$entity=EncuestaEvaluacionRespuesta::where('encuestaevaluacion_id',$request->query('encuestaevaluacion_id'))
			->where('pregunta_id',$request->query('pregunta_id'))
			->where('opcion_id',$request->query('opcion_id'))
			->where('evaluador_id',$request->query('evaluador_id'))
			->first();
			if($entity){
				EncuestaEvaluacionRespuesta::destroy($entity->id);
			}
			$entity=EncuestaEvaluacionRespuesta::create($data);
			$success=true;
			$entity=$entity->toArray();

		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}



}

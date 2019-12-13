<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Encuesta;
use Illuminate\Http\Request;
use App\models\EncuestaEvaluacion;
use App\Http\Controllers\Controller;

class EncuestaEvaluacionsController extends Controller {

	public function index(Request $request)
	{
		$filter=$request->query("filter");
		$grupoevaluacion_id=(!isset($filter['grupoevaluacion_id']))?'':$filter['grupoevaluacion_id'];
		$usuario_evaluador_id=(!isset($filter['usuario_evaluador_id']))?'':$filter['usuario_evaluador_id'];
		$encuesta_id=(!isset($filter['encuesta_id']))?'':$filter['encuesta_id'];

		$entities = EncuestaEvaluacion::with('respuestas')
		->where(function($q) use($grupoevaluacion_id,$usuario_evaluador_id,$encuesta_id){
			if($grupoevaluacion_id!='')
				$q=$q->where('grupoevaluacion_id','=',$grupoevaluacion_id);

			if($usuario_evaluador_id!='')
				$q=$q->where('usuario_evaluador_id','=',$usuario_evaluador_id);

			if($encuesta_id!='')
				$q=$q->where('encuesta_id','=',$encuesta_id);
			return $q;
		})
		->paginate($request->query('count',9999));

		return Response()->json($entities,200);
	}


	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), EncuestaEvaluacion::$rules);
		if (!$validator->fails())
		{
			$entity=EncuestaEvaluacion::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}


	public function update(Request $request, $id)
	{
		$success=false;
		$concurso = EncuestaEvaluacion::findOrFail($id);

		$validator = Validator::make($data = $request->all(), EncuestaEvaluacion::$rules);
		if (!$validator->fails())
		{
			$concurso->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$concurso), 201);
	}


}

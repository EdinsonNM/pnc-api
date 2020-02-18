<?php
namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\models\EncuestaPregunta;
use App\Http\Controllers\Controller;
use App\models\EncuestaPreguntaOpcion;

class EncuestaPreguntasController extends Controller {


	public function index(Request $request)
	{
		$encuesta_id=$request->query('encuesta_id','');
		$grupopregunta_id=$request->query('grupopregunta_id','');
		$encuestapreguntas = EncuestaPregunta::with(array('children'=>function($q){
				return $q->with('opciones');
		}))
		->where(function($q) use($encuesta_id,$grupopregunta_id){
			if($encuesta_id!='')
				$q=$q->where('encuesta_id',$encuesta_id);
			if($grupopregunta_id!='')
				$q=$q->where('grupopregunta_id',$grupopregunta_id);
			return $q;
		})
		->orderBy('orden','asc')
		->get();

		return Response()->json($encuestapreguntas,200);
	}

	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), EncuestaPregunta::$rules);

		if (!$validator->fails())
		{
			$entity=EncuestaPregunta::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}


	public function show($id)
	{
		$encuestapregunta = Encuestapregunta::findOrFail($id);

		return Response()->json($encuestapregunta,200);
	}

	/**
	 * Update the specified encuestapregunta in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$success=false;
		$concurso = EncuestaPregunta::findOrFail($id);

		$validator = Validator::make($data = $request->all(), EncuestaPregunta::$rules);

		if (!$validator->fails())
		{
			$concurso->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$concurso), 201);
	}


	public function destroy($id)
	{
		$success=false;
		$message='';
		$preguntas=EncuestaPregunta::where('grupopregunta_id',$id)->get();
		if(count($preguntas)==0){
			EncuestaPreguntaOpcion::where('pregunta_id',$id)->delete();
			EncuestaPregunta::destroy($id);
			$success=true;
		}else{
			$message="Es necesario primero eliminar las preguntas asociadas";
		}

		return Response()->json(array('success' => $success, 'message'=>$message),200);
	}

}

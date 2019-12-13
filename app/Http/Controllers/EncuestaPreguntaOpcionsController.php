<?php
namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\models\EncuestaPregunta;
use App\Http\Controllers\Controller;
use App\models\EncuestaPreguntaOpcion;

class EncuestaPreguntaOpcionsController extends Controller {


	public function index(Request $request)
	{
		$pregunta_id=$request->query('pregunta_id','');
		$entities = EncuestaPreguntaOpcion::where(function($q) use($pregunta_id,$grupopregunta_id){
			if($pregunta_id!='')
				$q=$q->where('pregunta_id',$pregunta_id);
			return $q;
		})->get();

		return Response()->json($entities,200);
	}

	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), EncuestaPreguntaOpcion::$rules);

		if (!$validator->fails())
		{
			$entity=EncuestaPreguntaOpcion::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$concurso = EncuestaPreguntaOpcion::findOrFail($id);

		$validator = Validator::make($data = $request->all(), EncuestaPreguntaOpcion::$rules);

		if (!$validator->fails())
		{
			$concurso->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$concurso), 201);
	}

	public function destroy($id)
	{
		$success=true;
		$message='';
		EncuestaPreguntaOpcion::destroy($id);

		return Response()->json(array('success' => $success, 'message'=>$message),200);
	}

}

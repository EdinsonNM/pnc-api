<?php

namespace App\Http\Controllers;

use Validator;
use App\models\AspectoClave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AspectoClavesController extends Controller {

	public function index(Request $request)
	{


		$evaluacion_id=$request->query('evaluacion_id','');
		$concursocriterio_id=$request->query('concursocriterio_id','');


		$entities = AspectoClave::with('creado_por')
		->with('actualizado_por')
		->where(function($q) use($evaluacion_id,$concursocriterio_id){
			if($evaluacion_id!=''){
				$q=$q->where('evaluacion_id','=',$evaluacion_id);
			}
			if($concursocriterio_id!=''){
				$q=$q->where('concursocriterio_id','=',$concursocriterio_id);
			}


			return $q;
		})->get();

		return Response()->json($entities,200);
	}

	public function store(Request $request)
	{
		$entity=null;
		$msg='';
		$success=false;
		$validator = Validator::make($data = $request->all(), AspectoClave::$rules);

		if (!$validator->fails())
		{
			$entity=AspectoClave::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}


	public function show($id)
	{
		$aspectoclave = Aspectoclave::findOrFail($id);

		return View::make('aspectoclaves.show', compact('aspectoclave'));
	}


	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = AspectoClave::findOrFail($id);

		$validator = Validator::make($data = $request->all(), AspectoClave::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;


			CriterioAspectoClave::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('aspectoclave_id','=',$entity->id)
			->update(array('estado'=>$entity->estado));

		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		$success=true;
		AspectoClave::destroy($id);

		return Response()->json(array('success'=>$success));
	}

}

<?php

namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\models\CriterioInforme;
use App\models\CriterioAprobacion;
use App\Http\Controllers\Controller;

class CriterioInformesController extends Controller {

	public function index(Request $request)
	{
		$evaluacion_id=$request->query('evaluacion_id','');
		$concursocriterio_id=$request->query('concursocriterio_id','');
		$tipo=$request->query('tipo','');

		$entities = CriterioInforme::where(function($q) use($tipo,$evaluacion_id,$concursocriterio_id){
			if($evaluacion_id!=''){
				$q=$q->where('evaluacion_id','=',$evaluacion_id);
			}
			if($concursocriterio_id!=''){
				$q=$q->where('concursocriterio_id','=',$concursocriterio_id);
			}
			if($tipo!=''){
				$q=$q->where('tipo','=',$tipo);
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
		$validator = Validator::make($data = $request->all(), CriterioInforme::$rules);

		if (!$validator->fails())
		{

			$entity=CriterioInforme::create($data);
			$success=true;

			CriterioAprobacion::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('concursocriterio_id','=', $entity->concursocriterio_id)
      ->delete();
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}


	public function show($id)
	{
		$criterioinforme = Criterioinforme::findOrFail($id);

		return Response()->json($criterioinforme,200);
	}


	/**
	 * Update the specified criterioinforme in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = CriterioInforme::findOrFail($id);

		$validator = Validator::make($data = $request->all(), CriterioInforme::$rules);

		if (!$validator->fails())
		{
			CriterioAprobacion::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('concursocriterio_id','=', $entity->concursocriterio_id)
      ->delete();
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		Criterioinforme::destroy($id);

		return Redirect::route('criterioinformes.index');
	}

}

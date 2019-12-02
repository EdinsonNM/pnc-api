<?php

namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\models\CriterioVisita;
use App\models\CriterioAprobacion;
use App\Http\Controllers\Controller;

class CriterioVisitasController extends Controller {

	/**
	 * Display a listing of criteriovisitas
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$evaluacion_id=$request->query('evaluacion_id','');
		$concursocriterio_id=$request->query('concursocriterio_id','');

		$entities = CriterioVisita::where(function($q) use($evaluacion_id,$concursocriterio_id){
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
		$validator = Validator::make($data = $request->all(), CriterioVisita::$rules);

		if (!$validator->fails())
		{
			$entity=CriterioVisita::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}


	public function show($id)
	{
		$criteriovisita = Criteriovisita::findOrFail($id);

		return View::make('criteriovisitas.show', compact('criteriovisita'));
	}


	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = CriterioVisita::findOrFail($id);

		$validator = Validator::make($data = $request->all(), CriterioVisita::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
			CriterioAprobacion::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('concursocriterio_id','=', $entity->concursocriterio_id)
            ->delete();
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}


	public function destroy($id)
	{
		Criteriovisita::destroy($id);

		return Redirect::route('criteriovisitas.index');
	}

}

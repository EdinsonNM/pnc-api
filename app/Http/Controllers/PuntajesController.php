<?php

namespace App\Http\Controllers;

use Validator;
use App\models\Puntaje;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\models\CriterioAprobacion;
use App\Http\Controllers\Controller;

class PuntajesController extends Controller {

	public function index(Request $request)
	{
		$evaluacion_id=$request->query('evaluacion_id','');
		$concursocriterio_id=$request->query('concursocriterio_id','');

		$entities = Puntaje::where(function($q) use($evaluacion_id,$concursocriterio_id){
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
		$validator = Validator::make($data = $request->all(), Puntaje::$rules);

		if (!$validator->fails())
		{
			$entity=Puntaje::create($data);
			$success=true;

			CriterioAprobacion::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('concursocriterio_id','=', $entity->concursocriterio_id)
            ->delete();
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}


	public function show($id)
	{
		$puntaje = Puntaje::findOrFail($id);

		return Response()->json($puntaje->toArray(),200);
	}


	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = Puntaje::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Puntaje::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;

			CriterioAprobacion::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('concursocriterio_id','=', $entity->concursocriterio_id)
            ->delete();
            //->update(array('aprobado' => 0));
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		Puntaje::destroy($id);

		return Redirect::route('puntajes.index');
	}

}

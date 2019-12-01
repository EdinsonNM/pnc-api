<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class AspectoClavesController extends \BaseController {

	/**
	 * Display a listing of aspectoclaves
	 *
	 * @return Response
	 */
	public function index()
	{


		$evaluacion_id=Input::get('evaluacion_id','');
		$concursocriterio_id=Input::get('concursocriterio_id','');


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

		return Response::json($entities,200);
	}

	/**
	 * Show the form for creating a new aspectoclave
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('aspectoclaves.create');
	}

	/**
	 * Store a newly created aspectoclave in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$entity=null;
		$msg='';
		$success=false;
		$validator = Validator::make($data = Input::all(), AspectoClave::$rules);

		if (!$validator->fails())
		{
			$entity=AspectoClave::create($data);
			$success=true;
		}

		return Response::json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}

	/**
	 * Display the specified aspectoclave.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$aspectoclave = Aspectoclave::findOrFail($id);

		return View::make('aspectoclaves.show', compact('aspectoclave'));
	}

	/**
	 * Show the form for editing the specified aspectoclave.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$aspectoclave = Aspectoclave::find($id);

		return View::make('aspectoclaves.edit', compact('aspectoclave'));
	}

	/**
	 * Update the specified aspectoclave in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$success=false;
		$msg='';
		$entity = AspectoClave::findOrFail($id);

		$validator = Validator::make($data = Input::all(), AspectoClave::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;


			CriterioAspectoClave::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('aspectoclave_id','=',$entity->id)
			->update(array('estado'=>$entity->estado));

		}

		return Response::json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	/**
	 * Remove the specified aspectoclave from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$success=true;
		AspectoClave::destroy($id);

		return Response::json(array('success'=>$success));
	}

}

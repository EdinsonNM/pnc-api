<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\CriterioAspectoClave;

class CriterioAspectoClavesController extends Controller {

	/**
	 * Display a listing of criterioaspectoclaves
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		$evaluacion_id=$request->query('evaluacion_id','');
		$concursocriterio_id=$request->query('concursocriterio_id','');

		$entities = CriterioAspectoClave::with(array('aspectoclave'=>function($q){
			return $q->with('criterio');
		}))->where(function($q) use($evaluacion_id,$concursocriterio_id){
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
			$entity=CriterioAspectoClave::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}


	public function show($id)
	{
		$criterioaspectoclave = Criterioaspectoclave::findOrFail($id);

		return View::make('criterioaspectoclaves.show', compact('criterioaspectoclave'));
	}


	public function update(Request $request, $id)
	{

		$success=false;
		$msg='';
		$entity = CriterioAspectoClave::findOrFail($id);

		$validator = Validator::make($data = $request->all(), CriterioAspectoClave::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		$success=true;
		CriterioAspectoClave::destroy($id);

		return Response()->json(array('success'=>$success));
	}

	public function disponibles(Request $request)
	{

		$evaluacion_id=$request->query('evaluacion_id','');
		$concursocriterio_id=$request->query('concursocriterio_id','');

		$criterioaspectos = CriterioAspectoClave::where('evaluacion_id','=',$evaluacion_id)
		->where('concursocriterio_id','=',$concursocriterio_id)
		->get();

		$ids=array();
		foreach ($criterioaspectos as $criterio) {
			$ids[]=$criterio->aspectoclave_id;
		}

		if(count($ids)>0){
			$entities =AspectoClave::with('criterio')

			->where('evaluacion_id','=',$evaluacion_id)
			->whereNotIn('id',$ids)
			->where('estado','=','1')
			->get();
		}else{
			$entities =AspectoClave::with('criterio')
			->where('estado','=','1')
			->where('evaluacion_id','=',$evaluacion_id)
			->get();
		}


		return Response()->json($entities->toArray(),200);

	}


}

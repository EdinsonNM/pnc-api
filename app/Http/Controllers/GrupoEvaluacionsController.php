<?php
namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\models\GrupoEvaluacion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GrupoEvaluacionsController extends Controller {


	public function index(Request $request)
	{
		$filter=$request->query("filter");
		$nombre=(!isset($filter['nombre']))?'':$filter['nombre'];
		$estado=(!isset($filter['estado']))?'':$filter['estado'];
		$estadoConcurso=$request->query('estadoConcurso','');
		$concurso_id=$request->query('concurso_id','');


		$entities = GrupoEvaluacion::select(DB::raw('grupoevaluacion.*'))->with('concurso')
		->join('concurso','grupoevaluacion.concurso_id','=','concurso.id')
		->where(function($q) use($nombre,$estado,$concurso_id,$estadoConcurso){
			if($nombre!='')
				$q=$q->where('nombre','like','%'.$nombre.'%');

			if($estado!='')
				$q=$q->where('estado','=',$estado);

			if($concurso_id!='')
				$q=$q->where('concurso_id','=',$concurso_id);

			if($estadoConcurso!='')
				$q=$q->where('concurso.estado','=',$estadoConcurso);

			return $q;
		})
		->paginate($request->query('count',9999));

		return Response()->json($entities,200);
	}

	public function store(Request $request)
	{
		$entity=null;
		$msg='';
		$success=false;
		$validator = Validator::make($data = $request->all(), GrupoEvaluacion::$rules);

		if (!$validator->fails())
		{
			$entity=GrupoEvaluacion::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function show($id)
	{
		$grupoevaluacion = GrupoEvaluacion::findOrFail($id);

		return Response()->json($grupoevaluacion->toArray());
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = GrupoEvaluacion::findOrFail($id);

		$validator = Validator::make($data = $request->all(), GrupoEvaluacion::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		GrupoEvaluacion::destroy($id);

		return Response()->json(array('success'=>true));
	}

}

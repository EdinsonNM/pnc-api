<?php

namespace App\Http\Controllers;

use Validator;
use App\models\Etapa;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EtapasController extends Controller {


	public function index(Request $request)
	{
		$filter=$request->query("filter");
		$tipoetapa_id=(!isset($filter['tipoetapa_id']))?'':$filter['tipoetapa_id'];
		$tipoconcurso_id=(!isset($filter['tipoconcurso_id']))?'':$filter['tipoconcurso_id'];
		$nombre=(!isset($filter['nombre']))?'':$filter['nombre'];

		$etapas = Etapa::with('TipoEtapa')
		->with('TipoConcurso')
		->where(function($q) use($tipoetapa_id,$tipoconcurso_id,$nombre){
			if($tipoetapa_id!='')
				$q=$q->where('tipoetapa_id','=',$tipoetapa_id);

			if($tipoconcurso_id!='')
				$q=$q->where('tipoconcurso_id','=',$tipoconcurso_id);

			if($nombre!='')
				$q=$q->where('nombre','like',"%$nombre%");

		})->paginate($request->query('count'));

		return Response()->json($etapas,200);
	}


	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), Etapa::$rules);

		if (!$validator->fails())
		{
			$entity=Etapa::create($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$etapa = Etapa::findOrFail($id);

		return View::make('etapas.show', compact('etapa'));
	}


	public function update(Request $request, $id)
	{
		$success=false;
		$entity = Etapa::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Etapa::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function destroy($id)
	{
		Etapa::destroy($id);

		return Response()->json(array('success' => true),200);
	}

}

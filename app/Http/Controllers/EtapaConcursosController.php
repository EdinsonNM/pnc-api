<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\models\EtapaConcurso;
use App\Http\Controllers\Controller;

class EtapaConcursosController extends Controller {

	public function index(Request $request)
	{

		$concurso_id=$request->query('concurso_id','');
		$etapa_id=$request->query('etapa_id','');
		$etapas = EtapaConcurso::where(function($q) use($concurso_id,$etapa_id){
			if($concurso_id!='')
				$q=$q->where('concurso_id','=',$concurso_id);

			if($etapa_id!='')
				$q=$q->where('etapa_id','=',$etapa_id);
			return $q;
		})->get();

		return Response()->json($etapas,200);
	}

	public function store(Request $request)
	{


		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), EtapaConcurso::$rules);

		if (!$validator->fails())
		{
			$entity=EtapaConcurso::create($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}


	public function show($id)
	{
		$etapaconcurso = EtapaConcurso::findOrFail($id);

		return View::make('etapaconcursos.show', compact('etapaconcurso'));
	}


	public function update(Request $request, $id)
	{

		$success=false;
		$entity = EtapaConcurso::findOrFail($id);

		$validator = Validator::make($data = $request->all(), EtapaConcurso::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function destroy($id)
	{
		EtapaConcurso::destroy($id);

		return Response()->json(array('success' => true),200);
	}

}

<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Encuesta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EncuestasController extends Controller {

	public function index(Request $request)
	{

		$filter=$request->query("filter");
		$concurso_id=(!isset($filter['concurso_id']))?'':$filter['concurso_id'];
		$tipoencuesta_id=(!isset($filter['tipoencuesta_id']))?'':$filter['tipoencuesta_id'];

		$encuestas = Encuesta::with('tipo_encuesta')
		->with('concurso')
		->where(function($q) use($concurso_id,$tipoencuesta_id){
			if($concurso_id!='')
				$q=$q->where('concurso_id','=',$concurso_id);

			if($tipoencuesta_id!='')
				$q=$q->where('tipoencuesta_id','=',$tipoencuesta_id);
			return $q;
		})
		->paginate($request->query('count',9999));

		return Response()->json($encuestas,200);

	}

	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), Encuesta::$rules);
		$data['inicio']=DateTime::createFromFormat('d/m/Y', $data['inicio'])->format('Y-m-d');
		$data['fin']=DateTime::createFromFormat('d/m/Y', $data['fin'])->format('Y-m-d');

		if (!$validator->fails())
		{
			$entity=Encuesta::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$encuesta = Encuesta::findOrFail($id);
		return Response()->json($encuesta,200);
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$concurso = Encuesta::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Encuesta::$rules);
		$data['inicio']=DateTime::createFromFormat('d/m/Y', $data['inicio'])->format('Y-m-d');
		$data['fin']=DateTime::createFromFormat('d/m/Y', $data['fin'])->format('Y-m-d');

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
		Encuesta::destroy($id);

		return Response()->json(array('success' => $success, 'message'=>$message),200);
	}

}

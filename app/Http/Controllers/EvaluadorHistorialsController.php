<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\models\EvaluadorHistorial;
use App\Http\Controllers\Controller;

class EvaluadorHistorialsController extends Controller {


	public function index(Request $request)
	{
		$evaluador_id=$request->query('evaluador_id','');
		$entities = EvaluadorHistorial::with('concurso')
		->with('evaluador')
		->where('evaluador_id','=',$evaluador_id)
		->paginate($request->query('count'));

		return Response()->json($entities,200);
	}

	public function store(Request $request)
	{

		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), EvaluadorHistorial::$rules);

		if (!$validator->fails())
		{
			$entity=EvaluadorHistorial::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$entity = EvaluadorHistorial::findOrFail($id);
		return Response()->json($entity,200);
	}

	public function update(Request $request, $id)
	{
		$evaluadorhistorial = Evaluadorhistorial::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Evaluadorhistorial::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$evaluadorhistorial->update($data);

		return Redirect::route('evaluadorhistorials.index');
	}

	public function destroy($id)
	{
		EvaluadorHistorial::destroy($id);

		return Response()->json(array('success'=>true));
	}

}

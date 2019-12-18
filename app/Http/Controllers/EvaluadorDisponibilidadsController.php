<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\EvaluadorDisponibilidad;

class EvaluadorDisponibilidadsController extends Controller {


	public function store(Request $request)
	{
		$entity=null;
		$msg='';
		$success=false;
		$validator = Validator::make($data = $request->all(), EvaluadorDisponibilidad::$rules);

		if (!$validator->fails())
		{
			$entity=EvaluadorDisponibilidad::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}


	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = EvaluadorDisponibilidad::findOrFail($id);

		$validator = Validator::make($data = $request->all(), EvaluadorDisponibilidad::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('sucess'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		Evaluadordisponibilidad::destroy($id);

		return Redirect::route('evaluadordisponibilidads.index');
	}

}

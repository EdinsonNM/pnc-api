<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\InscripcionEvaluador;

class InscripcionEvaluadorsController extends Controller {


	public function store(Request $request)
	{

		$entity=null;
		$success=false;
		$message='';
		$validator = Validator::make($data = $request->all(), InscripcionEvaluador::$rules);

		if (!$validator->fails())
		{
			$entity=InscripcionEvaluador::create($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity,'message'=>$message), 201);

	}

	public function update(Request $request, $id)
	{
		$inscripcionevaluador = Inscripcionevaluador::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Inscripcionevaluador::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$inscripcionevaluador->update($data);

		return Redirect::route('inscripcionevaluadors.index');
	}

	public function destroy($id)
	{

		$success=false;
		$msg='';
		InscripcionEvaluador::destroy($id);
		$success=true;
		/*$inscripcion=InscripcionEvaluador::find($id);
		$grupos=GrupoEvaluacionEvaluador::where('evaluador_id','=',$inscripcion->evaluador_id);
		if(count($grupos)>0){
			$msg="Inscripción del Evaluador ya se encuentra asignado a un grupo de evaluación";
		}else{
			InscripcionEvaluador::destroy($id);
			$success=true;
		}*/


		return Response()->json(array('success' => $success, 'message'=>$msg), 201);

	}

}

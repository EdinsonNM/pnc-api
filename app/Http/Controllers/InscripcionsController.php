<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Inscripcion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InscripcionsController extends Controller {

	public function index(Request $request)
	{
		$concurso_id=$request->query('concurso_id','');
		$postulante_id=$request->query('postulante_id','');
		$inscripcions = Inscripcion::where(function($q) use($concurso_id,$postulante_id){
			if($concurso_id!='')
				$q=$q->where('concurso_id','=',$concurso_id);
			if($postulante_id!='')
				$q=$q->where('postulante_id','=',$postulante_id);
			return $q;
		})->get();

		return Response()->json($inscripcions,200);
	}


	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$message='';
		$validator = Validator::make($data = $request->all(), Inscripcion::$rules);

		if (!$validator->fails())
		{
			$entity=Inscripcion::create($data);
			$success=true;
		}



		return Response()->json(array('success' => $success, 'entity'=>$entity->toArray(),'message'=>$message), 201);
	}

	public function show($id)
	{
		$inscripcion = Inscripcion::with('impactosproyecto')->findOrFail($id);

		return Response()->json($inscripcion);
	}


	public function update(Request $request, $id)
	{

		$success=false;
		$entity=null;
		$inscripcion = Inscripcion::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Inscripcion::$rules);

		if (!$validator->fails())
		{
			$inscripcion->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$inscripcion), 201);

	}


	public function destroy($id)
	{
		$success=false;
		$msg='';
		$inscripcion=Inscripcion::with('evaluaciones')->find($id);
		if(count($inscripcion->evaluaciones)>0){
			$msg="Inscripción ya se encuentra asociada a un proceso de evaluación";
		}else{
			Inscripcion::destroy($id);
			$success=true;
		}


		return Response()->json(array('success' => $success, 'message'=>$msg), 201);
	}

}

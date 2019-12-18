<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\GrupoEvaluacionEvaluadorPermiso;

class GrupoEvaluacionEvaluadorPermisosController extends Controller {

	/**
	 * Display a listing of grupoevaluacionevaluadorpermisos
	 *
	 * @return Response
	 */
	public function index(Requedt $request)
	{
		$ge_evaluador_id=$request->query('ge_evaluador_id','');
		$ge_postulante_id=$request->query('ge_postulante_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');

		$entities = GrupoEvaluacionEvaluadorPermiso::where(
			function($q) use($ge_evaluador_id,$ge_postulante_id,$tipoetapa_id){
				if($ge_evaluador_id!=''){
					$q=$q->where('grupoevaluacionevaluador_id','=',$grupoevaluacionevaluador_id);
				}
				if($ge_postulante_id!=''){
					$q=$q->where('grupoevaluacionpostulante_id','=',$ge_postulante_id);
				}
				if($tipoetapa_id!=''){
					$q=$q->where('tipoetapa_id','=',$tipoetapa_id);
				}
				return $q;
			})->get();

		return Response()->json($entities, 200);
	}


	public function store(Request $request)
	{
		$validator = Validator::make($data = $request->all(), GrupoEvaluacionEvaluadorPermiso::$rules);
		$entity=null;
		$success=false;
		$message='';
		if (!$validator->fails())
		{
			$entity=GrupoEvaluacionEvaluadorPermiso::create($data);
			$success=true;
		}
		return Response()->json(array('success' => $success, 'entity'=>$entity,'message'=>$message), 201);
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = GrupoEvaluacionEvaluadorPermiso::findOrFail($id);

		$validator = Validator::make($data = $request->all(), GrupoEvaluacionEvaluadorPermiso::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}


}

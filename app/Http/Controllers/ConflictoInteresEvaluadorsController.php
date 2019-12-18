<?php

namespace App\Http\Controllers;

use DateTime;
use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\ConflictoInteresEvaluador;

class ConflictoInteresEvaluadorsController extends Controller {

	public function index(Request $request)
	{
		$evaluador_id=$request->query('evaluador_id','');
		$entities = ConflictoInteresEvaluador::with('TipoVinculo')
			->where(function($q) use($evaluador_id){
			if($evaluador_id!='')
				$q=$q->where('evaluador_id','=',$evaluador_id);
				return $q;
			})
			->get();

		return Response()->json($entities,200);
	}

	/**
	 * Store a newly created conflictointeresevaluador in storage.
	 *
	 * @return Response
	 */
	public function store(Request $requedt)
	{
		$entity=null;
		$msg='';
		$success=false;
		$data = $request->all();
		$data['fecini']=DateTime::createFromFormat('d/m/Y', $data['fecini'])->format('Y-m-d');
		if($request->query('fecfin','')!='')
			$data['fecfin']=DateTime::createFromFormat('d/m/Y', $data['fecfin'])->format('Y-m-d');
		$validator = Validator::make($data, ConflictoInteresEvaluador::$rules);

		if (!$validator->fails())
		{
			$entity=ConflictoInteresEvaluador::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$conflictointeresevaluador = ConflictoInteresEvaluador::findOrFail($id);
		//$conflictointeresevaluador->fecini=date("d-m-Y", strtotime($conflictointeresevaluador->fecini));
		//$conflictointeresevaluador->fecfin=date("d-m-Y", strtotime($conflictointeresevaluador->fecfin));
		return Response()->json($conflictointeresevaluador,200);
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = ConflictoInteresEvaluador::findOrFail($id);
		$data = $request->all();
		$data['fecini']=DateTime::createFromFormat('d/m/Y', $data['fecini'])->format('Y-m-d');
		if($request->query('fecfin','')!='')
			$data['fecfin']=DateTime::createFromFormat('d/m/Y', $data['fecfin'])->format('Y-m-d');
		$validator = Validator::make($data, ConflictoInteresEvaluador::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}


	public function destroy($id)
	{
		ConflictoInteresEvaluador::destroy($id);

		return Response()->json(array('success' => true),200);
	}

}

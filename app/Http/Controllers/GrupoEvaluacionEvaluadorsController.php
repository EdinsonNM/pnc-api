<?php
namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\models\GrupoEvaluacion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\models\InscripcionEvaluador;
use App\models\GrupoEvaluacionEvaluador;
use App\models\GrupoEvaluacionEvaluadorPermiso;

class GrupoEvaluacionEvaluadorsController extends Controller {

	/**
	 * Display a listing of grupoevaluacionevaluadors
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$grupoevaluacion_id=$request->query('grupoevaluacion_id','');
		$grupoevaluacionpostulante_id=$request->query('grupoevaluacionpostulante_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');

		$entitites = GrupoEvaluacionEvaluador::with(array('inscripcion'=>function($q){
			return $q->with('evaluador');
		}))
		->with(array('permisos'=>function($q) use($grupoevaluacionpostulante_id,$tipoetapa_id){
			if($grupoevaluacionpostulante_id!='')
				$q=$q->where('grupoevaluacionpostulante_id','=',$grupoevaluacionpostulante_id);

			if($tipoetapa_id!='')
				$q=$q->where('tipoetapa_id','=',$tipoetapa_id);

			return $q;
		}))
		->where(function($q) use($grupoevaluacion_id){
			if($grupoevaluacion_id!='')
				$q=$q->where('grupoevaluacion_id','=',$grupoevaluacion_id);
			return $q;
		})->get();

		return Response()->json($entitites,200);
	}

	public function store(Request $request)
	{
		$validator = Validator::make($data = $request->all(), GrupoEvaluacionEvaluador::$rules);
		$entity=null;
		$success=false;
		$message='';
		if (!$validator->fails())
		{
			$entity=GrupoEvaluacionEvaluador::create($data);
			$success=true;
		}



		return Response()->json(array('success' => $success, 'entity'=>$entity,'message'=>$message), 201);
	}

	public function show($id)
	{
		$grupoevaluacionevaluador = GrupoEvaluacionEvaluador::findOrFail($id);

		return View::make('grupoevaluacionevaluadors.show', compact('grupoevaluacionevaluador'));
	}

	public function update(Request $request, $id)
	{
		$grupoevaluacionevaluador = GrupoEvaluacionEvaluador::findOrFail($id);

		$validator = Validator::make($data = $request->all(), GrupoEvaluacionEvaluador::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$grupoevaluacionevaluador->update($data);

		return Redirect::route('grupoevaluacionevaluadors.index');
	}

	public function destroy($id)
	{
		GrupoEvaluacionEvaluadorPermiso::where('grupoevaluacionevaluador_id','=',$id)->delete();
		GrupoEvaluacionEvaluador::destroy($id);

		return Response()->json(array('success'=>true),200);
	}

	public function EvaluadoresDisponibles($grupoevaluacion_id){
		$grupoevaluacion = GrupoEvaluacion::findOrFail($grupoevaluacion_id);

		$EvaluadoresInGroup=GrupoEvaluacionEvaluador::join('grupoevaluacion', 'grupoevaluacion.id', '=', 'grupoevaluacionevaluador.grupoevaluacion_id')
			->where('grupoevaluacion.concurso_id','=',$grupoevaluacion->concurso_id)
			->get();

		$registers=array();
		foreach ($EvaluadoresInGroup as $evaluador) {
			$registers[]=$evaluador->inscripcion_id;
		}

		if(count($registers)>0){
			$entities=InscripcionEvaluador::with('evaluador')
				->where('concurso_id','=',$grupoevaluacion->concurso_id)
				->whereNotIn('id', $registers)
				->get();
		}
		else{
			$entities=InscripcionEvaluador::with('evaluador')
				->where('concurso_id','=',$grupoevaluacion->concurso_id)
				->get();
		}

		return Response()->json($entities,200);
	}

	public function GruposEvaluador($evaluador_id){
		$inscripciones=InscripcionEvaluador::where('evaluador_id','=',$evaluador_id)->get();
		$registers=array();
		foreach ($inscripciones as $ins) {
			$registers[]=$ins->id;
		}

		$grupos=GrupoEvaluacionEvaluador::with(array('grupoevaluacion'=>function($q){
			return $q->with('concurso');
		}))
		->select(DB::raw("grupoevaluacionevaluador.*"))
		->join('inscripcionevaluador','inscripcionevaluador.id','=','grupoevaluacionevaluador.inscripcion_id')
		->join('concurso','concurso.id','=','inscripcionevaluador.concurso_id')
		->where('concurso.estado','=','1')
		->whereIn('inscripcion_id',$registers)->get();

		return Response()->json($grupos,200);
	}


	public function Lider(Request $request){
		$id=$request->query('id','');
		$value=$request->query('value','');
		$evaluador=GrupoEvaluacionEvaluador::find($id);
		GrupoEvaluacionEvaluador::where('grupoevaluacion_id','=',$evaluador->grupoevaluacion_id)->update(array('lider'=>0));
		$evaluador->lider=$value;
		$evaluador->save();
		return Response()->json(array('success'=>true),200);

	}

}

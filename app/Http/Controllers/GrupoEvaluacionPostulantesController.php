<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Inscripcion;
use Illuminate\Http\Request;
use App\models\GrupoEvaluacion;
use App\Http\Controllers\Controller;
use App\models\GrupoEvaluacionPostulante;

class GrupoEvaluacionPostulantesController extends Controller {

	/**
	 * Display a listing of grupoevaluacionpostulantes
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$estadoConcurso=$request->query('estadoConcurso','');//agregado para test
		$concurso_id=$request->query('concurso_id','');
		$visita=$request->query('visita','');
		$grupoevaluacion_id=$request->query('grupoevaluacion_id','');
		$grupoevaluacionpostulantes = GrupoEvaluacionPostulante::select('grupoevaluacionpostulante.*')
		->with(array('inscripcion'=>function($q){
			return $q->with('postulante');
		}))
		->with(array('grupoevaluacion'=>function($q){
			return $q->with('concurso');
		}))
		->with('tipopremio')
		->join('inscripcion','inscripcion.id','=','grupoevaluacionpostulante.inscripcion_id')//agregado para test
		->join('concurso','concurso.id','=','inscripcion.concurso_id')//agregado para test
		->where(function($q) use($grupoevaluacion_id,$estadoConcurso,$concurso_id,$visita){
			if($grupoevaluacion_id!='')
				$q=$q->where('grupoevaluacion_id','=',$grupoevaluacion_id);
			if($estadoConcurso!='')//agregado para test
				$q=$q->where('concurso.estado','=',$estadoConcurso);//agregado para test
			if($concurso_id!='')//agregado para asignar visita
				$q=$q->where('concurso.id','=',$concurso_id);
			if($visita!='')//postulantes que pasaron o no a visita
				$q=$q->where('visita','=',$visita);
			return $q;
		})->get();

		return Response()->json($grupoevaluacionpostulantes,200);
	}

	/**
	 * Show the form for creating a new grupoevaluacionpostulante
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('grupoevaluacionpostulantes.create');
	}

	/**
	 * Store a newly created grupoevaluacionpostulante in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), GrupoEvaluacionPostulante::$rules);
		$entity=null;
		$success=false;
		$message='';
		if (!$validator->fails())
		{
			$entity=GrupoEvaluacionPostulante::create($data);
			$success=true;
		}



		return Response()->json(array('success' => $success, 'entity'=>$entity,'message'=>$message), 201);
	}

	/**
	 * Display the specified grupoevaluacionpostulante.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$grupoevaluacionpostulante = GrupoEvaluacionPostulante::findOrFail($id);

		return View::make('grupoevaluacionpostulantes.show', compact('grupoevaluacionpostulante'));
	}

	/**
	 * Show the form for editing the specified grupoevaluacionpostulante.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$grupoevaluacionpostulante = GrupoEvaluacionPostulante::find($id);

		return View::make('grupoevaluacionpostulantes.edit', compact('grupoevaluacionpostulante'));
	}

	/**
	 * Update the specified grupoevaluacionpostulante in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$success=false;
		$concurso = GrupoEvaluacionPostulante::findOrFail($id);

		$validator = Validator::make($data = Input::all(), GrupoEvaluacionPostulante::$rules);

		if (!$validator->fails())
		{
			$concurso->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$concurso), 201);
	}

	/**
	 * Remove the specified grupoevaluacionpostulante from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		GrupoEvaluacionPostulante::destroy($id);

		return Response()->json(array('success'=>true),200);
	}


	public function PostulantesDisponibles($grupoevaluacion_id){
		$grupoevaluacion = GrupoEvaluacion::findOrFail($grupoevaluacion_id);

		$PostulantesInGroup=GrupoEvaluacionPostulante::join('grupoevaluacion', 'grupoevaluacion.id', '=', 'grupoevaluacionpostulante.grupoevaluacion_id')
			->where('grupoevaluacion.concurso_id','=',$grupoevaluacion->concurso_id)
			->get();

		$registers=array();
		foreach ($PostulantesInGroup as $postulante) {
			$registers[]=$postulante->inscripcion_id;
		}

		if(count($registers)>0){
			$entities=Inscripcion::with('postulante')
				->where('concurso_id','=',$grupoevaluacion->concurso_id)
				->whereNotIn('id', $registers)
				->get();
		}
		else{
			$entities=Inscripcion::with('postulante')
				->where('concurso_id','=',$grupoevaluacion->concurso_id)
				->get();
		}

		return Response()->json($entities,200);
	}

}
